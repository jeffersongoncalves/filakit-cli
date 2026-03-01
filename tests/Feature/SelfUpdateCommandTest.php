<?php

use App\Services\SelfUpdateService;

it('fails when not running as PHAR', function () {
    $service = Mockery::mock(SelfUpdateService::class);
    $service->shouldReceive('isRunningAsPhar')->once()->andReturn(false);
    $this->app->instance(SelfUpdateService::class, $service);

    $this->artisan('self-update')
        ->expectsOutputToContain('only available when running as a PHAR')
        ->assertExitCode(1);
});

it('shows already up to date when on latest version', function () {
    $service = Mockery::mock(SelfUpdateService::class);
    $service->shouldReceive('isRunningAsPhar')->once()->andReturn(true);
    $service->shouldReceive('getCurrentVersion')->once()->andReturn('1.2.0');
    $service->shouldReceive('getLatestRelease')->once()->andReturn([
        'tag' => 'v1.2.0',
        'url' => 'https://example.com/filakit.phar',
    ]);
    $service->shouldReceive('isUpdateAvailable')->once()->with('1.2.0', 'v1.2.0')->andReturn(false);
    $this->app->instance(SelfUpdateService::class, $service);

    $this->artisan('self-update')
        ->expectsOutputToContain('already using the latest version')
        ->assertExitCode(0);
});

it('checks for update without installing with --check flag', function () {
    $service = Mockery::mock(SelfUpdateService::class);
    $service->shouldReceive('isRunningAsPhar')->once()->andReturn(true);
    $service->shouldReceive('getCurrentVersion')->once()->andReturn('1.0.0');
    $service->shouldReceive('getLatestRelease')->once()->andReturn([
        'tag' => 'v1.2.0',
        'url' => 'https://example.com/filakit.phar',
    ]);
    $service->shouldReceive('isUpdateAvailable')->once()->with('1.0.0', 'v1.2.0')->andReturn(true);
    $service->shouldNotReceive('download');
    $service->shouldNotReceive('replacePhar');
    $this->app->instance(SelfUpdateService::class, $service);

    $this->artisan('self-update', ['--check' => true])
        ->expectsOutputToContain('new version is available')
        ->assertExitCode(0);
});

it('downloads and replaces PHAR on update', function () {
    $service = Mockery::mock(SelfUpdateService::class);
    $service->shouldReceive('isRunningAsPhar')->once()->andReturn(true);
    $service->shouldReceive('getCurrentVersion')->once()->andReturn('1.0.0');
    $service->shouldReceive('getLatestRelease')->once()->andReturn([
        'tag' => 'v1.2.0',
        'url' => 'https://example.com/filakit.phar',
    ]);
    $service->shouldReceive('isUpdateAvailable')->once()->with('1.0.0', 'v1.2.0')->andReturn(true);
    $service->shouldReceive('download')->once()->with('https://example.com/filakit.phar')->andReturn('/tmp/filakit_tmp');
    $service->shouldReceive('replacePhar')->once()->with('/tmp/filakit_tmp');
    $this->app->instance(SelfUpdateService::class, $service);

    $this->artisan('self-update')
        ->expectsOutputToContain('Successfully updated')
        ->assertExitCode(0);
});

it('fails gracefully when GitHub API fails', function () {
    $service = Mockery::mock(SelfUpdateService::class);
    $service->shouldReceive('isRunningAsPhar')->once()->andReturn(true);
    $service->shouldReceive('getCurrentVersion')->once()->andReturn('1.0.0');
    $service->shouldReceive('getLatestRelease')->once()->andThrow(
        new RuntimeException('Failed to fetch latest release from GitHub (HTTP 403).')
    );
    $this->app->instance(SelfUpdateService::class, $service);

    $this->artisan('self-update')
        ->expectsOutputToContain('Failed to fetch latest release')
        ->assertExitCode(1);
});

it('fails gracefully when download fails', function () {
    $service = Mockery::mock(SelfUpdateService::class);
    $service->shouldReceive('isRunningAsPhar')->once()->andReturn(true);
    $service->shouldReceive('getCurrentVersion')->once()->andReturn('1.0.0');
    $service->shouldReceive('getLatestRelease')->once()->andReturn([
        'tag' => 'v1.2.0',
        'url' => 'https://example.com/filakit.phar',
    ]);
    $service->shouldReceive('isUpdateAvailable')->once()->andReturn(true);
    $service->shouldReceive('download')->once()->andThrow(
        new RuntimeException('Failed to download the PHAR file (HTTP 500).')
    );
    $this->app->instance(SelfUpdateService::class, $service);

    $this->artisan('self-update')
        ->expectsOutputToContain('Failed to download the PHAR file')
        ->assertExitCode(1);
});
