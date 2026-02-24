<?php

namespace App\Providers;

use App\Services\InstallerService;
use App\Services\StarterKitService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StarterKitService::class);
        $this->app->singleton(InstallerService::class);
    }

    public function boot(): void
    {
        //
    }
}
