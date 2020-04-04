<?php

namespace App\Services\Sign;

use App\Repositories\User as UserRepo;

class User
{
    public $id;

    public $stamp;

    private $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
        $this->id = $user->id;
    }

    public function __get($key)
    {
        return $this->user->$key;
    }

    public function salt()
    {
        return $this->user->salt;
    }

    public function flush()
    {
        return $this->user->flush();
    }

    public function __sleep()
    {
        return ['id', 'stamp'];
    }

    public function __wakeup()
    {
        $this->user = UserRepo::find($this->id);
    }
}
