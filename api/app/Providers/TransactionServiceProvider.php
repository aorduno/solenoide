<?php

namespace App\Providers;

use App\Core\Services\Impl\TransactionServiceImpl;
use App\Core\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TransactionService::class, function ($app) {
            return new TransactionServiceImpl();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return array
     */
    public function provides()
    {
        return [TransactionService::class];
    }
}
