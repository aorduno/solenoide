<?php

namespace App\Providers;

use App\Core\Services\FileManagerService;
use App\Core\Services\Impl\FileManagerServiceImpl;
use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FileManagerService::class, function ($app) {
            return new FileManagerServiceImpl();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return array
     */
    public function provides()
    {
        return [FileManagerService::class];
    }
}
