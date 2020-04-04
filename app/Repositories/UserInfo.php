<?php

namespace App\Repositories;

use Illuminate\Support\Str;

class UserInfo extends _
{
    const ITEM_SECRET = 0;
    const ITEM_ACCOUNT = 1;
    const ITEM_PASSHASH = 2;
    const ITEM_NAME = 3;
    const ITEM_AVATAR = 4;
    const ITEM_EMAIL = 5;
    const ITEM_TELEPHONE = 6;

    const ITEM_DICT = [
        self::ITEM_SECRET => 'secret',
        self::ITEM_ACCOUNT => 'account',
        self::ITEM_PASSHASH => 'passhash',
        self::ITEM_NAME => 'name',
        self::ITEM_AVATAR => 'avatar',
        self::ITEM_EMAIL => 'email',
        self::ITEM_TELEPHONE => 'telephone',
    ];

    const STATUS_VALID = 1;

    const STATUS_DICT = [
        0 => null,
        self::STATUS_VALID => 'valid'
    ];

    public $timestamps = false;

    protected $table = 'user_info';

    protected $fillable = ['user_id', 'item', 'data', 'status'];

    public static function constITEM($name)
    {
        return self::const("ITEM_$name");
    }

    public function itemModify(int $item, string $data, int $status = 0)
    {
        exit("modify info $item:$data");
        return self::updateOrCreate(
            ['item' => $item, 'data' => $data],
            ['user_id' => $this->user_id]
        );
    }
}
