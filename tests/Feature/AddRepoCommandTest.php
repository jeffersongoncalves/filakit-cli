<?php

use App\Services\RepositoryService;
use Laravel\Prompts\Prompt;

beforeEach(function () {
    Prompt::fallbackWhen(true);
});

it('registers a private repository when ssh access works', function () {
    $service = Mockery::mock(RepositoryService::class);
    $service->shouldReceive('canAccess')
        ->once()
        ->with('git@github.com:vendor/my-skeleton.git')
        ->andReturn(true);
    $service->shouldReceive('register')
        ->once()
        ->with('vendor-my-skeleton', 'git@github.com:vendor/my-skeleton.git')
        ->andReturn(true);
    $this->app->instance(RepositoryService::class, $service);

    $this->artisan('add-repo', ['url' => 'git@github.com:vendor/my-skeleton.git'])
        ->expectsOutputToContain('--kit=vendor/my-skeleton')
        ->assertExitCode(0);
});

it('prompts for the url when not provided', function () {
    $service = Mockery::mock(RepositoryService::class);
    $service->shouldReceive('canAccess')->once()->andReturn(true);
    $service->shouldReceive('register')
        ->once()
        ->with('vendor-my-skeleton', 'ssh://git@gitlab.com/vendor/my-skeleton')
        ->andReturn(true);
    $this->app->instance(RepositoryService::class, $service);

    $this->artisan('add-repo')
        ->expectsQuestion('What is the SSH URL of the private repository?', 'ssh://git@gitlab.com/vendor/my-skeleton')
        ->assertExitCode(0);
});

it('rejects a non ssh url', function () {
    $service = Mockery::mock(RepositoryService::class);
    $service->shouldNotReceive('canAccess');
    $this->app->instance(RepositoryService::class, $service);

    $this->artisan('add-repo', ['url' => 'https://github.com/vendor/my-skeleton.git'])
        ->expectsOutputToContain('Invalid SSH URL')
        ->assertExitCode(1);
});

it('shows ssh setup instructions when access fails', function () {
    $service = Mockery::mock(RepositoryService::class);
    $service->shouldReceive('canAccess')->once()->andReturn(false);
    $service->shouldNotReceive('register');
    $this->app->instance(RepositoryService::class, $service);

    $this->artisan('add-repo', ['url' => 'git@github.com:vendor/my-skeleton.git'])
        ->expectsOutputToContain('ssh -T git@github.com')
        ->assertExitCode(1);
});

it('fails when composer config fails', function () {
    $service = Mockery::mock(RepositoryService::class);
    $service->shouldReceive('canAccess')->once()->andReturn(true);
    $service->shouldReceive('register')->once()->andReturn(false);
    $this->app->instance(RepositoryService::class, $service);

    $this->artisan('add-repo', ['url' => 'git@github.com:vendor/my-skeleton.git'])
        ->expectsOutputToContain('Failed to register')
        ->assertExitCode(1);
});
