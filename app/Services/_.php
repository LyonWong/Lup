<?php

namespace App\Services;

class _ 
{
    protected $available = [];

    public function __get($key)
    {
        if (in_array($key, $this->available)) {
            return $this->$key;
        } else {
            return null;
        }
    }
}