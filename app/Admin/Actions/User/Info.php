<?php

namespace App\Admin\Actions\User;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Info extends RowAction
{
    public $name = 'Info';

    public function href()
    {
        return 'user-info';
    }

}