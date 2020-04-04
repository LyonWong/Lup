<?php

namespace App\Services;

use App\Repositories\UserInfo as UserInfoRepo;

class UserInfo
{
    const ITEM_SECRET = 'secret';
    const ITEM_ACCOUNT = 'account';
    const ITEM_PASSHASH = 'passhash';
    const ITEM_NAME = 'name';
    const ITEM_AVATAR = 'avatar';
    const ITEM_EMAIL = 'email';
    const ITEM_TELEPHONE = 'telephone';

    const ITEM_DICT = [
        UserInfoRepo::ITEM_SECRET => self::ITEM_SECRET,
        UserInfoRepo::ITEM_ACCOUNT => self::ITEM_ACCOUNT,
        UserInfoRepo::ITEM_PASSHASH => self::ITEM_PASSHASH,
        UserInfoRepo::ITEM_NAME => self::ITEM_NAME,
        UserInfoRepo::ITEM_AVATAR => self::ITEM_AVATAR,
        UserInfoRepo::ITEM_EMAIL => self::ITEM_EMAIL,
        UserInfoRepo::ITEM_TELEPHONE => self::ITEM_TELEPHONE,
    ];

    protected $attributes = [
        'status' => 0
    ];

    public function itemModify(int $userId, int $item, int $data, int $status=0)
    {
        return UserInfoRepo::updateOrCreate(
            [
                'item' => $item,
                'data' => $data,
                'status' => $status
            ],
            ['user_id' => $userId]
        );
    }

    public function itemsModify($userId, $items)
    {

    }

    public function avatarUpload(){}

    public function avatarRestore(){}


}

