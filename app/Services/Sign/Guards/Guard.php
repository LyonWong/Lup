<?php

namespace App\Services\Sign\Guards;

use App\Services\Sign\User;
use App\Services\Sign\Storages\Storage;
use Illuminate\Http\Request;

interface Guard 
{
    public function __construct(
        Storage $storage,
        Request $request,
        array $conf
    );

    /**
     * if the user signed in
     */
    public function check();

    /**
     * signed in
     */
    public function checkIn(User $user);

    /**
     * signed out
     */
    public function checkOut();

    /**
     * get user
     */
    public function checkUser();

    /**
     * sign out all
     */
    public function flush();
}