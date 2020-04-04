<?php

namespace App\Services;

use App\Exceptions\SignException;
use App\Repositories\User as UserRepo;
use App\Repositories\UserInfo as UserInfoRepo;
use App\Repositories\UserSign as UserSignRepo;
use App\Services\UserInfo as UserInfoServ;

class UserSign extends _
{

    protected $available = ['repo'];

    public function register(int $type, $code): UserSignRepo
    {
        if ($this->match($type, $code)) {
            $TYPE = UserSignRepo::TYPE_DICT[$type];
            throw new SignException("Duplicate register $TYPE:`$code`");
        }
        $userSignRepo = new UserSignRepo;
        $userSignRepo->type = $type;
        $userSignRepo->user_id = 0;
        $userSignRepo->code = $code;
        $userSignRepo->save();
        if ($userRepo = $this->generate()) {
            $userSignRepo->user_id = $userRepo->id;
            $userSignRepo->save();
        }
        return $userSignRepo;
    }

    public function generate()
    {
        $userRepo = new UserRepo;
        $try = 10;
        while ($try--) {
            $userRepo->sn = uniqid();
            $userRepo->flush(false);
            if ($userRepo->save()) {
                return $userRepo;
            }
        }
        return false;
    }

    public function associate($userId, $type, $code):bool
    {
        $userSignRepo = new UserSignRepo;
        $userSignRepo->type = $type;
        $userSignRepo->code = $code;
        $userSignRepo->user_id = $userId;
        return $userSignRepo->save();
    }

    public function match(int $type, $code): ?UserSignRepo
    {
        $userSign = new UserSignRepo;
        $userSign->type = $type;
        $userSign->code = $code;
        return $userSign->match();
    }

    public function passwordModify($userId, $password): UserInfoRepo
    {
        $userInfoRepo = new UserInfoRepo;
        $userInfoRepo->user_id = $userId;
        $res = UserInfoRepo::updateOrInsert(
            ['user_id' => $userId, 'item' => UserInfoRepo::ITEM_PASSHASH],
            ['data' => password_hash($password, PASSWORD_DEFAULT)]
        );
        return $res->first();
    }

    public function passwordVerify($identity, $password): bool
    {
        $userSignRepo = UserSignRepo::where('code', $identity)->whereIn('type', [
            UserSignRepo::TYPE_ACCOUNT,
            UserSignRepo::TYPE_EMAIL,
            UserSignRepo::TYPE_TELEPHONE
        ])->first();
        if (!$userSignRepo) {
            return false;
        }

        $passhash = UserInfoRepo::where([
            ['user_id', '=', $userSignRepo->user_id],
            ['item', '=', UserInfoRepo::ITEM_PASSHASH]
        ])->first('data');
        if (!$passhash) {
            return false;
        }

        if (password_verify($password, $passhash->data)) {
            return $userSignRepo;
        } else {
            return false;
        }
    }
}
