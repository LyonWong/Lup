<?php

namespace App\Services\Sign\Storages;

use Illuminate\Support\Facades\Redis;

class RedisStorage implements Storage
{
    public $prefix = '';

    protected $redis;

    public function __construct($connection)
    {
        $this->redis = Redis::connection($connection);
    }

    public function get($key)
    {
        return unserialize($this->redis->get($this->prefix.$key));
    }

    public function set($key, $value, $ttl)
    {
        return $this->redis->setex($this->prefix.$key, $ttl, serialize($value));
    }

    public function delete($key)
    {
        return $this->redis->del($this->prefix.$key);
    }

    public function exists($key)
    {
        return $this->redis->exists($this->prefix.$key);
    }

    public function ttl($key)
    {
        return $this->redis->ttl($key);
    }

    public function __call($method, $args)
    {
        $args[0] = $this->prefix . $args[0];
        return $this->redis->$method(...$args);
    }
}