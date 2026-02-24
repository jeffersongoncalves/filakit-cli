<?php

use App\Services\InstallerService;
use App\Services\StarterKitService;
use Laravel\Prompts\Prompt;

beforeEach(function () {
    Prompt::fallbackWhen(true);
});

it('displays available starter kits and creates app', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getStarterKitOptions')
        ->once()
        ->andReturn([
            'jeffersongoncalves/filakitv5' => 'Filakit v5',
            'jeffersongoncalves/nativekitv5' => 'Nativekit v5',
        ]);
    $this->app->instance(StarterKitService::class, $service);

    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/filakitv5', Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', ['name' => 'test-app'])
        ->expectsQuestion('Which starter kit would you like to use?', 'jeffersongoncalves/filakitv5')
        ->expectsOutputToContain('test-app')
        ->assertExitCode(0);
});

it('accepts kit option to skip interactive selection', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldNotReceive('getStarterKitOptions');
    $this->app->instance(StarterKitService::class, $service);

    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/filakitv5', Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', [
        'name' => 'test-app',
        '--kit' => 'jeffersongoncalves/filakitv5',
    ])
        ->expectsOutputToContain('jeffersongoncalves/filakitv5')
        ->assertExitCode(0);
});

it('prompts for app name when not provided', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getStarterKitOptions')
        ->once()
        ->andReturn([
            'jeffersongoncalves/filakitv5' => 'Filakit v5',
        ]);
    $this->app->instance(StarterKitService::class, $service);

    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('my-app', 'jeffersongoncalves/filakitv5', Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new')
        ->expectsQuestion('What is the name of your application?', 'my-app')
        ->expectsQuestion('Which starter kit would you like to use?', 'jeffersongoncalves/filakitv5')
        ->expectsOutputToContain('my-app')
        ->assertExitCode(0);
});

it('fails when no starter kits are available', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getStarterKitOptions')
        ->once()
        ->andReturn([]);
    $this->app->instance(StarterKitService::class, $service);

    $this->artisan('new', ['name' => 'test-app'])
        ->expectsOutputToContain('No starter kits available')
        ->assertExitCode(1);
});

it('fails when installer process fails', function () {
    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->andReturn(false);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', [
        'name' => 'test-app',
        '--kit' => 'jeffersongoncalves/filakitv5',
    ])
        ->expectsOutputToContain('Failed to create the application')
        ->assertExitCode(1);
});
