<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->singleton(TransactionService::class, function ($app) {
//            return new TransactionServiceImpl();
//        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return array
     */
    public function boot()
    {
//        return [TransactionService::class];
    }
}
