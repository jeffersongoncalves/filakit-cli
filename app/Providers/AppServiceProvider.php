<?php

namespace App\Providers;

use App\Services\InstallerService;
use App\Services\StarterKitService;
use Illuminate\Support\ServiceProvider;
use JeffersonGoncalves\LaravelZero\SelfUpdate\PharUpdater;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StarterKitService::class);
        $this->app->singleton(InstallerService::class);

        $this->app->singleton(PharUpdater::class, fn () => new PharUpdater(
            githubRepo: 'jeffersongoncalves/filakit-cli',
            assetName: 'filakit.phar',
            tempPrefix: 'filakit_',
            currentVersion: (string) config('app.version', 'unreleased'),
        ));
    }

    public function boot(): void
    {
        //
    }
}
