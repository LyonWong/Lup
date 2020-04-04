<?php

namespace App\Http\Controllers\Sign;

use App\Http\Traits\ActionTrait;

class Email extends _
{
    use ActionTrait;
    public function _GET_()
    {
        echo 'get email';
    }

    public function _POST()
    {}
}