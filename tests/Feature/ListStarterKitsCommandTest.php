<?php

use App\DTOs\StarterKit;
use App\Services\StarterKitService;

it('lists all starter kits grouped by version', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getAvailableVersions')
        ->once()
        ->andReturn(['v5', 'v4']);
    $service->shouldReceive('getStarterKitsByVersion')
        ->with('v5')
        ->andReturn([
            new StarterKit('Base v5', 'filakitphp/basev5'),
            new StarterKit('Filakit v5', 'jeffersongoncalves/filakitv5'),
        ]);
    $service->shouldReceive('getStarterKitsByVersion')
        ->with('v4')
        ->andReturn([
            new StarterKit('Base v4', 'filakitphp/basev4'),
        ]);
    $this->app->instance(StarterKitService::class, $service);

    $this->artisan('list:starterkits')
        ->expectsOutputToContain('Filament v5')
        ->expectsOutputToContain('filakitphp/basev5')
        ->expectsOutputToContain('Filament v4')
        ->expectsOutputToContain('filakitphp/basev4')
        ->assertExitCode(0);
});

it('filters starter kits by version', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getStarterKitsByVersion')
        ->with('v5')
        ->andReturn([
            new StarterKit('Base v5', 'filakitphp/basev5'),
            new StarterKit('Filakit v5', 'jeffersongoncalves/filakitv5'),
        ]);
    $this->app->instance(StarterKitService::class, $service);

    $this->artisan('list:starterkits', ['--filament' => 'v5'])
        ->expectsOutputToContain('Filament v5')
        ->expectsOutputToContain('filakitphp/basev5')
        ->expectsOutputToContain('jeffersongoncalves/filakitv5')
        ->assertExitCode(0);
});

it('shows error for version with no kits', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getStarterKitsByVersion')
        ->with('v2')
        ->andReturn([]);
    $this->app->instance(StarterKitService::class, $service);

    $this->artisan('list:starterkits', ['--filament' => 'v2'])
        ->expectsOutputToContain('No starter kits available for v2')
        ->assertExitCode(1);
});

it('shows error when no starter kits exist', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getAvailableVersions')
        ->once()
        ->andReturn([]);
    $this->app->instance(StarterKitService::class, $service);

    $this->artisan('list:starterkits')
        ->expectsOutputToContain('No starter kits available')
        ->assertExitCode(1);
});
