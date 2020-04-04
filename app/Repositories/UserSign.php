<?php

namespace App\Repositories;

use Illuminate\Support\Str;

class UserSign extends _
{
    const TYPE_PASSWORD = 0;
    const TYPE_ACCOUNT = 1;
    const TYPE_EMAIL = 2;
    const TYPE_TELEPHONE = 3;


    const TYPE_WEIXIN = 1 << 8;
    const TYPE_WEIXIN_MOBILE = self::TYPE_WEIXIN + 1;
    const TYPE_WEIXIN_WEBSITE = self::TYPE_WEIXIN + 2;
    const TYPE_WEIXIN_OFFICIAL = self::TYPE_WEIXIN + 3;

    const TYPE_ALIPAY = 2 << 8;

    const TYPE_DICT = [
        self::TYPE_PASSWORD => 'password',
        self::TYPE_ACCOUNT => 'account',
        self::TYPE_EMAIL => 'email',
        self::TYPE_TELEPHONE => 'telephone',

        self::TYPE_WEIXIN => 'weixin',
        self::TYPE_WEIXIN => 'weixin-mobile',
        self::TYPE_WEIXIN_WEBSITE => 'weixin-website',
        self::TYPE_WEIXIN_OFFICIAL => 'weixin-official',
    ];

    public $timestamps = false;

    protected $table = 'user_sign';

    public static function constTYPE($name)
    {
        return self::const("TYPE_$name");
    }

    public function user()
    {
        return $this->belongsTo('App\Repositories\User');
    }

    public function info($item = null)
    {
        $res = $this->hasMany('App\Repositories\UserInfo', 'user_id', 'user_id');
        if ($item === null) {
            return $res;
        } else {
            return $res->where('item', $item)->first();
        }
    }

}
