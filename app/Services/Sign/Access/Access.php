<?php

namespace App\Services\Sign\Access;

use App\Services\Sign\User;
use App\Services\UserSign as UserSignService;

interface Access
{
    /**
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app, UserSignService $userSignService);

    public function verify(array $certificates): ?User;
}