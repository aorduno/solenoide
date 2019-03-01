<?php

namespace App\Providers;

use App\Core\Services\CsvValidatorService;
use App\Core\Services\FileManagerService;
use App\Core\Services\Impl\CsvValidatorServiceImpl;
use App\Core\Services\Impl\FileManagerServiceImpl;
use App\Core\Services\Impl\NotificationServiceImpl;
use App\Core\Services\Impl\TransactionUploadServiceImpl;
use App\Core\Services\NotificationService;
use App\Core\Services\TransactionUploadService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationServiceImpl();
        });

        $this->app->singleton(TransactionUploadService::class, function ($app) {
            return new TransactionUploadServiceImpl($app->make(FileManagerService::class));
        });

        $this->app->singleton(FileManagerService::class, function ($app) {
            return new FileManagerServiceImpl();
        });

        $this->app->singleton(CsvValidatorService::class, function ($app) {
            return new CsvValidatorServiceImpl();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return array
     */
    public function provides()
    {
        return [NotificationService::class, TransactionUploadService::class,
            FileManagerService::class, CsvValidatorService::class];
    }
}
