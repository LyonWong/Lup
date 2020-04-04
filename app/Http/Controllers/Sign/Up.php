<?php

namespace App\Http\Controllers\Sign;

use App\Services\UserSign as UserSignService;
use App\Repositories\UserSign as UserSignRepo;
use Illuminate\Http\Request;

class Up extends _
{
    protected $userSignService;

    public function __construct(UserSignService $userSignService)
    {
        $this->userSignService= $userSignService;
    }

    public function __invoke(Request $request)
    {
        return $userSignRepo = $this->userSignService->register(
            UserSignRepo::TYPE_ACCOUNT,
            $request->account
        );
        return 'up';
    }
}
