<?php

use App\DTOs\StarterKit;
use App\Services\InstallerService;
use App\Services\StarterKitService;
use Laravel\Prompts\Prompt;

beforeEach(function () {
    Prompt::fallbackWhen(true);
});

it('asks for version then shows numbered starter kits', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getAvailableVersions')
        ->once()
        ->andReturn(['v5', 'v4', 'v3']);
    $service->shouldReceive('getStarterKitOptions')
        ->once()
        ->with('v5')
        ->andReturn([1 => 'Filakit v5', 2 => 'Nativekit v5']);
    $service->shouldReceive('findByIndex')
        ->once()
        ->with('v5', 1)
        ->andReturn(new StarterKit('Filakit v5', 'jeffersongoncalves/filakitv5'));
    $this->app->instance(StarterKitService::class, $service);

    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/filakitv5', [], Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', ['name' => 'test-app'])
        ->expectsQuestion('Which Filament version do you want to use?', 'v5')
        ->expectsQuestion('Which starter kit would you like to use?', 1)
        ->expectsOutputToContain('test-app')
        ->assertExitCode(0);
});

it('accepts --filament option to skip version selection', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getAvailableVersions')
        ->once()
        ->andReturn(['v5', 'v4']);
    $service->shouldReceive('getStarterKitOptions')
        ->once()
        ->with('v4')
        ->andReturn([1 => 'Filakit v4', 2 => 'Nativekit v4']);
    $service->shouldReceive('findByIndex')
        ->once()
        ->with('v4', 2)
        ->andReturn(new StarterKit('Nativekit v4', 'jeffersongoncalves/nativekitv4'));
    $this->app->instance(StarterKitService::class, $service);

    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/nativekitv4', [], Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', ['name' => 'test-app', '--filament' => 'v4'])
        ->expectsQuestion('Which starter kit would you like to use?', 2)
        ->expectsOutputToContain('test-app')
        ->assertExitCode(0);
});

it('accepts --kit option to skip all selection', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldNotReceive('getAvailableVersions');
    $this->app->instance(StarterKitService::class, $service);

    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/filakitv5', [], Mockery::type('callable'))
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
    $service->shouldReceive('getAvailableVersions')
        ->once()
        ->andReturn(['v5']);
    $service->shouldReceive('getStarterKitOptions')
        ->once()
        ->with('v5')
        ->andReturn([1 => 'Filakit v5']);
    $service->shouldReceive('findByIndex')
        ->once()
        ->with('v5', 1)
        ->andReturn(new StarterKit('Filakit v5', 'jeffersongoncalves/filakitv5'));
    $this->app->instance(StarterKitService::class, $service);

    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('my-app', 'jeffersongoncalves/filakitv5', [], Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new')
        ->expectsQuestion('What is the name of your application?', 'my-app')
        ->expectsQuestion('Which Filament version do you want to use?', 'v5')
        ->expectsQuestion('Which starter kit would you like to use?', 1)
        ->expectsOutputToContain('my-app')
        ->assertExitCode(0);
});

it('fails when no starter kits are available', function () {
    $service = Mockery::mock(StarterKitService::class);
    $service->shouldReceive('getAvailableVersions')
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

it('passes boolean flags to installer', function () {
    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/filakitv5', ['--git', '--pest', '--force'], Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', [
        'name' => 'test-app',
        '--kit' => 'jeffersongoncalves/filakitv5',
        '--git' => true,
        '--pest' => true,
        '--force' => true,
    ])->assertExitCode(0);
});

it('passes value options to installer', function () {
    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/filakitv5', ['--database=pgsql'], Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', [
        'name' => 'test-app',
        '--kit' => 'jeffersongoncalves/filakitv5',
        '--database' => 'pgsql',
    ])->assertExitCode(0);
});

it('passes --github with value to installer', function () {
    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/filakitv5', ['--github=public', '--organization=my-org'], Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', [
        'name' => 'test-app',
        '--kit' => 'jeffersongoncalves/filakitv5',
        '--github' => 'public',
        '--organization' => 'my-org',
    ])->assertExitCode(0);
});

it('passes --github without value to installer', function () {
    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/filakitv5', ['--github'], Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', [
        'name' => 'test-app',
        '--kit' => 'jeffersongoncalves/filakitv5',
        '--github' => '',
    ])->assertExitCode(0);
});

it('passes package manager flags to installer', function () {
    $installer = Mockery::mock(InstallerService::class);
    $installer->shouldReceive('run')
        ->once()
        ->with('test-app', 'jeffersongoncalves/filakitv5', ['--pnpm'], Mockery::type('callable'))
        ->andReturn(true);
    $this->app->instance(InstallerService::class, $installer);

    $this->artisan('new', [
        'name' => 'test-app',
        '--kit' => 'jeffersongoncalves/filakitv5',
        '--pnpm' => true,
    ])->assertExitCode(0);
});
