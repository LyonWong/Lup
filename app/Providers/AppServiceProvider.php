<?php

namespace App\Providers;

use App\Services\Notification\SMS\MockSMS;
use App\Services\Notification\SMS\SMS;
use App\Services\Sign\Manager;
use App\Services\UserSign;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        // Guard::class => Guard::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(Manager::class, function($app) {
            return new Manager($app);
        });
        $this->app->bind(UserSign::class, function($app) {
            return new UserSign;
        });
        $this->app->bind(SMS::class, function($app) {
            return new MockSMS;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
