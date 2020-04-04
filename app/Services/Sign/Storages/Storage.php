<?php

namespace App\Services\Sign\Storages;

interface Storage
{
    public function get($key);

    public function set($key, $value, $ttl);

    public function delete($key);

    public function exists($key);

    public function ttl($key);
}