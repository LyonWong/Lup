<?php

namespace App\Services\Sign\Access;

use App\Exceptions\SignException;
use App\Repositories\UserInfo as UserInfoRepo;
use App\Repositories\UserSign as UserSignRepo;
use App\Services\Sign\User;
use App\Services\UserSign as UserSignService;
use App\Services\UserInfo as UserInfoService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeixinAccess implements Access
{
    protected $userSignService;

    protected $userInfoService;

    public function __construct($app, UserSignService $userSignService)
    {
        $this->userSignService = $userSignService;
        $this->userInfoService = $app->make(UserInfoService::class);
    }

    public function verify(array $credentials)
    {
        $entry = $credentials['door'] . 'Entry';
        return $this->$entry($credentials['code']);
    }

    public function offcialVerify($code)
    {
        return $this->oauthVerify(
            UserSignRepo::TYPE_WEIXIN_OFFICIAL,
            $code,
            [
                'APP_ID' => env('WX_OFFICIAL_APP_ID'),
                'SECRET' => env('WX_OFFICIAL_SECRET')
            ]
        );
    }

    public function websiteVerify($code)
    {
        return $this->oauthVerify(
            UserSignRepo::TYPE_WEIXIN_WEBSITE,
            $code,
            [
                'APP_ID' => env('WX_WEBSITE_APP_ID'),
                'SECRET' => env('WX_WEBSITE_SECRET')
            ]
        );
    }

    protected function oauthVerify(int $type, $code, $config)
    {
        $res = Http::get('https://api.weixin.qq.com/sns/oauth2/access_token', [
            'appid' => $config['APP_ID'],
            'secret' => $config['SECRET'],
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]);
        if (isset($res['errcode'])) {
            Log::Error("Weixin-website access error", $res);
            throw new SignException("Weixin access error: $res[errmsg]");
        }

        $userSignRepo = $this->userSignService->match($type, $res['openid']);
        if ($userSignRepo) { // openid matched
            return new User($userSignRepo->user);
        }

        $info = Http::get('https://api.weixin.qq.com/sns/userinfo', [
            'access_token' => $res['access_token'],
            'openid' => $res['openid'],
            'lang' => 'zh_CN'
        ]);

        if (isset($info['unionid'])) {
            $userSignRepo = $this->userSignService->match($type, $info['unionid']);
            if ($userSignRepo) { // unionid matched
                $this->userSignService->associate(
                    $userSignRepo->user_id,
                    UserSignRepo::TYPE_WEIXIN_WEBSITE,
                    $info['openid']
                );
                return new User($userSignRepo->user);
            } else { // register with unionid
                $userSignRepo = $this->userSignService->register(
                    UserSignRepo::TYPE_WEIXIN,
                    $info['unionid']
                );
            }
        } else { // register with openid
            $userSignRepo = $this->userSignService->register($type, $info['unionid']);
        }
        $userSignRepo->user->info()->itemModify(
            UserInfoRepo::ITEM_NAME,
            $info['nickname']
        );

        return new User($userSignRepo->user);
    }

    public function miniprogramVerify($credentials)
    {
        $code = $credentials['code'];
        if ($code == 'the code is a mock one') {
            $uaid = base64_encode($code);
            $openId = 'mocked';
        } else {
            $res = Http::inst()->get('https://api.weixin.qq.com/sns/jscode2session', [
                'appid' => env('WX_MINIPROGRAME_APP_ID'),
                'secret' => env('WX_MINIPROGRAME_APP_SECRET'),
                'js_code' => $code,
                'grant_type' => 'authorization_code'
            ]);
            $res = json_decode($res, true);
            $aesKey = base64_decode($res['session_key']);
            $aesIV = base64_decode($credentials['iv']);
            $aesCipher = base64_decode($credentials['encryptedData']);
            $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
            $info = json_decode($result, true);
            if ($info['watermark']['appid'] != env('WX_MINIPROGRAME_APP_ID')) {
                return false;
            }
            $uaid = $info['unionId'];
            $openId = $info['openId'];
        }

        $info['name'] = $info['nickName'] ?? 'unnamed';
        $info['avatar'] = $info['avatarUrl'] ?? 'null';

        if (isset($info['unionid'])) {
            $userSignRepo = $this->userSignService->match(UserSignRepo::TYPE_WEIXIN, $info['unionid']);
            if ($userSignRepo) { // unionid matched
                $this->userSignService->associate(
                    $userSignRepo->user_id,
                    UserSignRepo::TYPE_WEIXIN_WEBSITE,
                    $info['openid']
                );
                return new User($userSignRepo->user);
            } else { // register with unionid
                $userSignRepo = $this->userSignService->register(UserSignRepo::TYPE_WEIXIN, $info['unionid']);
            }
        } else { // register with openid
            $userSignRepo = $this->userSignService->register(UserSignRepo::TYPE_WEIXIN_WEBSITE, $info['unionid']);
        }
        $this->userInfoService->infoModify([
            UserInfoRepo::ITEM_NAME => $info['nickname'],
            UserInfoRepo::ITEM_AVATAR => $info['headimgurl']
        ]);
        return new User($userSignRepo->user);
    }
}
