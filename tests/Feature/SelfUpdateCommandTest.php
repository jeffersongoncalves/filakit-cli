<?php

use JeffersonGoncalves\LaravelZero\SelfUpdate\PharUpdater;

it('fails when not running as PHAR', function () {
    $updater = Mockery::mock(PharUpdater::class);
    $updater->shouldReceive('isRunningAsPhar')->once()->andReturn(false);
    $this->app->instance(PharUpdater::class, $updater);

    $this->artisan('self-update')
        ->expectsOutputToContain('only available when running as a PHAR')
        ->assertExitCode(1);
});

it('shows already up to date when on latest version', function () {
    $updater = Mockery::mock(PharUpdater::class);
    $updater->shouldReceive('isRunningAsPhar')->once()->andReturn(true);
    $updater->shouldReceive('getCurrentVersion')->once()->andReturn('1.2.0');
    $updater->shouldReceive('getLatestRelease')->once()->andReturn([
        'tag' => 'v1.2.0',
        'url' => 'https://example.com/filakit.phar',
    ]);
    $updater->shouldReceive('isUpdateAvailable')->once()->with('1.2.0', 'v1.2.0')->andReturn(false);
    $this->app->instance(PharUpdater::class, $updater);

    $this->artisan('self-update')
        ->expectsOutputToContain('already using the latest version')
        ->assertExitCode(0);
});

it('checks for update without installing with --check flag', function () {
    $updater = Mockery::mock(PharUpdater::class);
    $updater->shouldReceive('isRunningAsPhar')->once()->andReturn(true);
    $updater->shouldReceive('getCurrentVersion')->once()->andReturn('1.0.0');
    $updater->shouldReceive('getLatestRelease')->once()->andReturn([
        'tag' => 'v1.2.0',
        'url' => 'https://example.com/filakit.phar',
    ]);
    $updater->shouldReceive('isUpdateAvailable')->once()->with('1.0.0', 'v1.2.0')->andReturn(true);
    $updater->shouldNotReceive('download');
    $updater->shouldNotReceive('replacePhar');
    $this->app->instance(PharUpdater::class, $updater);

    $this->artisan('self-update', ['--check' => true])
        ->expectsOutputToContain('new version is available')
        ->assertExitCode(0);
});

it('fails gracefully when GitHub API fails', function () {
    $updater = Mockery::mock(PharUpdater::class);
    $updater->shouldReceive('isRunningAsPhar')->once()->andReturn(true);
    $updater->shouldReceive('getCurrentVersion')->once()->andReturn('1.0.0');
    $updater->shouldReceive('getLatestRelease')->once()->andThrow(
        new RuntimeException('Failed to fetch latest release from GitHub (HTTP 403).')
    );
    $this->app->instance(PharUpdater::class, $updater);

    $this->artisan('self-update')
        ->expectsOutputToContain('Failed to fetch latest release')
        ->assertExitCode(1);
});

it('fails gracefully when download fails', function () {
    $updater = Mockery::mock(PharUpdater::class);
    $updater->shouldReceive('isRunningAsPhar')->once()->andReturn(true);
    $updater->shouldReceive('getCurrentVersion')->once()->andReturn('1.0.0');
    $updater->shouldReceive('getLatestRelease')->once()->andReturn([
        'tag' => 'v1.2.0',
        'url' => 'https://example.com/filakit.phar',
    ]);
    $updater->shouldReceive('isUpdateAvailable')->once()->andReturn(true);
    $updater->shouldReceive('download')->once()->andThrow(
        new RuntimeException('Failed to download the PHAR file (HTTP 500).')
    );
    $this->app->instance(PharUpdater::class, $updater);

    $this->artisan('self-update')
        ->expectsOutputToContain('Failed to download the PHAR file')
        ->assertExitCode(1);
});
