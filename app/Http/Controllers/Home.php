<?php

namespace App\Http\Controllers;

class Home extends Signed
{
    public function __invoke()
    {
        return view('home');
    }
}