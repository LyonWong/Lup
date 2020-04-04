<?php

namespace App\Repositories;

use Illuminate\Support\Str;

class User extends _
{
    public $name = 'user-name';

    public $timestamps = false;

    protected $table = 'user';

    protected $saltLength = 8;

    public function info(int $item = null, $field = null)
    {
        $res = $this->hasMany('App\Repositories\UserInfo');
        if ($res===null) {
            return null;
        }
        if ($item === null) {
            return $res;
        }
        $row = $res->where('item', $item)->first();
        if ($row===null) {
            return null;
        }
        if ($field===null) {
            return $row;
        }
        return $row[$field];
    }

    public function sign(int $type = null)
    {
        $res = $this->hasMany('App\Repositories\UserSign');
        if ($type === null) {
            return $res;
        } else {
            return $res->where('type', $type)->first();
        }
    }

    public function flush($save = true)
    {
        $this->salt = Str::random($this->saltLength);
        if ($save) {
            $this->save();
        }
        return $this->salt;
    }
}
