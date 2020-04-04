<?php

namespace App\Services\Sign\Access;

use App\Services\Sign\User;
use App\Services\UserSign as UserSignService;

class PasswordAccess implements Access
{
    protected $userSignService;

    public function __construct($app, UserSignService $userSignService)
    {
        $this->userSignService = $userSignService;
    }

    /**
     * @params array $credentials <identity,password>
     */
    public function verify(array $credentials): ?User
    {
        if ($userSignRepo = $this->userSignService->passwordVerify(
            $credentials['identity'],
            $credentials['password']
        )) {
            return new User($userSignRepo->user);
        } else {
            return null;
        }
    }
}
