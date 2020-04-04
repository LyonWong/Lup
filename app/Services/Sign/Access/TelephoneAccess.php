<?php

namespace App\Services\Sign\Access;

use App\Exceptions\SignException;
use App\Repositories\UserSign as UserSignRepo;
use App\Repositories\UserInfo as UserInfoRepo;
use App\Services\Notification\SMS\SMS;
use App\Services\Sign\User;
use App\Services\UserSign as UserSignService;
use Illuminate\Support\Facades\Cache;

class TelephoneAccess implements Access
{
    protected $userSignService;

    protected $SMS;

    protected $codeLength = 6;

    protected $codeExpire = SECONDS_MINUTE * 5;

    protected $codeTrottle = SECONDS_MINUTE;

    public function __construct($app, UserSignService $userSignService)
    {
        $this->SMS = $app->make(SMS::class);
        $this->userSignService = $userSignService;
    }

    public function verify(array $credentials): ?User
    {
        $key = "code_$credentials[phoneNumber]";
        if (!$prepare = Cache::get($key)) {
            return null;
        }
        [$code, $time] = explode('@', $prepare);
        if ($code != $credentials['code']) {
            return null;
        }
        $userSignRepo = $this->userSignService->match(UserSignRepo::TYPE_TELEPHONE, $credentials['phoneNumber']);
        if (!$userSignRepo) {
            $userSignRepo = $this->userSignService->register(UserSignRepo::TYPE_TELEPHONE, $credentials['phoneNumber']);
            UserInfoRepo::updateOrCreate(
                [
                    'item' => UserInfoRepo::ITEM_TELEPHONE,
                    'data' => $credentials['phoneNumber'],
                    'status' => UserInfoRepo::STATUS_VALID
                ],
                ['user_id' => $userSignRepo->user_id]
            );
        }
        return new User($userSignRepo->user);
    }

    public function prepare($phoneNumber)
    {
        $code = $this->generateCode($this->codeLength);
        $now = time();
        $this->SMS->sendSignCode($phoneNumber, $code);
        $key = "code_$phoneNumber";
        if ($previous = Cache::get($key)) {
            [$_code, $_time] = explode('@', $previous);
            $countdown = $this->codeTrottle - $now + $_time;
            if ($countdown > 0) {
                throw new SignException("please try after $countdown seconds.");
            }
        }
        Cache::put($key, "$code@$now", $this->codeExpire);
        return $code;
    }


    protected function generateCode(int $length)
    {
        return rand(10 ** ($length - 1), 10 ** $length - 1);
    }
}
