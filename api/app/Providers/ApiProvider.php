<?php

namespace App\Providers;

use App\Core\Api\NotificationApi;
use App\Core\Api\TransactionUploadApi;
use App\Core\Api\Version1\NotificationApiVersion1;
use App\Core\Api\Version1\TransactionUploadApiVersion1;
use App\Core\Services\CsvValidatorService;
use App\Core\Services\NotificationService;
use App\Core\Services\TransactionUploadService;
use Illuminate\Support\ServiceProvider;

class ApiProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NotificationApi::class, function ($app) {
            return new NotificationApiVersion1($app->make(NotificationService::class));
        });

        $this->app->singleton(TransactionUploadApi::class, function ($app) {
            return new TransactionUploadApiVersion1($app->make(TransactionUploadService::class),
                $app->make(CsvValidatorService::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return array
     */
    public function provides()
    {
        return [NotificationApi::class, TransactionUploadApi::class];
    }
}
