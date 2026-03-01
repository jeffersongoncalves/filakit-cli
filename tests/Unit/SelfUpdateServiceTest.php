<?php

use App\Services\SelfUpdateService;

it('returns current version from config', function () {
    config()->set('app.version', '1.5.0');

    $service = new SelfUpdateService;

    expect($service->getCurrentVersion())->toBe('1.5.0');
});

it('returns unreleased when version is not set', function () {
    config()->set('app.version', 'unreleased');

    $service = new SelfUpdateService;

    expect($service->getCurrentVersion())->toBe('unreleased');
});

it('detects not running as PHAR in dev environment', function () {
    $service = new SelfUpdateService;

    expect($service->isRunningAsPhar())->toBeFalse();
});

it('detects update available when current is older', function () {
    $service = new SelfUpdateService;

    expect($service->isUpdateAvailable('1.0.0', 'v1.2.0'))->toBeTrue();
});

it('detects no update when current is same', function () {
    $service = new SelfUpdateService;

    expect($service->isUpdateAvailable('1.2.0', 'v1.2.0'))->toBeFalse();
});

it('detects no update when current is newer', function () {
    $service = new SelfUpdateService;

    expect($service->isUpdateAvailable('2.0.0', 'v1.2.0'))->toBeFalse();
});

it('treats unreleased as always needing update', function () {
    $service = new SelfUpdateService;

    expect($service->isUpdateAvailable('unreleased', 'v1.0.0'))->toBeTrue();
});

it('handles versions with v prefix', function () {
    $service = new SelfUpdateService;

    expect($service->isUpdateAvailable('v1.0.0', 'v1.2.0'))->toBeTrue()
        ->and($service->isUpdateAvailable('v1.2.0', 'v1.2.0'))->toBeFalse();
});

it('compares patch versions correctly', function () {
    $service = new SelfUpdateService;

    expect($service->isUpdateAvailable('1.2.0', 'v1.2.1'))->toBeTrue()
        ->and($service->isUpdateAvailable('1.2.1', 'v1.2.0'))->toBeFalse();
});
