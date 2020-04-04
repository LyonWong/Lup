<?php

namespace App\Services\Sign;

use App\Services\UserSign as UserSignService;
use Illuminate\Support\Facades\Redis;

class Manager
{
    protected $app;

    protected $name;

    protected $config = [];

    protected $guards = [];

    protected $access = [];

    /**
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function entry($name = null)
    {
        $config = $this->app['config']['sign'];
        $this->name = $name ?? $this->name ?? $config['default'];
        if (empty($this->guards[$this->name])) {
            $guard = $config['entries'][$this->name]['guard'];
            $conf = $config['guards'][$guard];
            $this->config[$this->name] = $config['entries'][$this->name];
            $this->guards[$this->name] = $this->guard($guard, $conf);
        }
        return $this->guards[$this->name];
    }

    public function access($name)
    {
        if (empty($this->access[$name])) {
            $class = __NAMESPACE__."\\Access\\{$name}Access";
            $this->access[$name] = new $class($this->app, $this->app->make(UserSignService::class));
        }
        return $this->access[$name];
    }

    public function name()
    {
        return $this->name;
    }

    public function config($field)
    {
        return $this->config[$this->name][$field] ?? null;
    }

    protected function guard($driver, $conf)
    {
        $class = __NAMESPACE__."\\Guards\\{$driver}Guard";
        $storage = $this->storage(...explode(':', $conf['storage']));
        $guard = new $class($storage, $this->app['request'], $conf);
        return $guard;
    }

    protected function storage($driver, $connection)
    {
        $class = __NAMESPACE__."\\Storages\\{$driver}Storage";
        $storage = new $class($connection);
        return $storage;
    }
}
