<?php

namespace App\Repositories;

class UserMemo extends _
{
    //
    public $timestamps = false;

    protected $table = 'user_memo';

    protected $attributes = [
        'data' => '{}',
    ];
}
