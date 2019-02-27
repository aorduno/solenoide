<?php

namespace App\Providers;

use App\Services\Impl\TransactionUploadServiceImpl;
use App\Services\TransactionUploadService;
use Illuminate\Support\ServiceProvider;

class TransactionUploadServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TransactionUploadService::class, function ($app) {
            return new TransactionUploadServiceImpl();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return array
     */
    public function provides()
    {
        return [TransactionUploadService::class];
    }
}
