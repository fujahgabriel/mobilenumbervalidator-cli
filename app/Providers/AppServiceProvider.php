<?php

namespace App\Providers;

use App\MobileNumberValidator;
use App\BaseValidator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MobileNumberValidator::class, function ($app) {
            return BaseValidator::getInstance();
        });
    }
}
