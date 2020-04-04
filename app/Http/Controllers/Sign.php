<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\ActionTrait;
use Illuminate\Http\Request;

class Sign extends Controller
{
    use ActionTrait;

    public function __invoke(Request $request)
    {
        $this->_action($request);
    }

    public function _GET_in(Request $request)
    {
        echo 'sign get in';
    }
}
