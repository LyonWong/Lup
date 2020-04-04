<?php

namespace App\Http\Controllers\Sign;

use App\Repositories\UserInfo as UserInfoRepo;
use App\Repositories\UserSign as UserSignRepo;

class User extends _
{

    public function _GET_()
    {
        $user = $this->manager->entry()->checkUser();

        $signs = [];
        foreach ($user->sign as $row) {
            $type = UserSignRepo::TYPE_DICT[$row['type']];
            $signs[$type] = [
                'type' => $type,
                'code' => $row['code'],
                'time' => $row['update_ts']
            ];
        }

        $infos = [];
        foreach ($user->info as $row) {
            $item =  UserInfoRepo::ITEM_DICT[$row['item']];
            $infos[$item] = [
                'item' => $item,
                'data' => $row['data'],
                'status' => UserInfoRepo::STATUS_DICT[$row['status']]
            ];
        }
        return view('sign.user', [
            'user' => $user,
            'signs' => $signs,
            'infos' => $infos,
        ]);
    }
}
