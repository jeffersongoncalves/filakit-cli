<?php

use App\DTOs\StarterKit;
use App\Services\StarterKitService;

it('reads starter kits from config', function () {
    config()->set('starterkits', [
        ['title' => 'Filakit v5', 'package' => 'jeffersongoncalves/filakitv5'],
        ['title' => 'Nativekit v5', 'package' => 'jeffersongoncalves/nativekitv5'],
        ['title' => 'Filakit v4', 'package' => 'jeffersongoncalves/filakitv4'],
    ]);

    $service = new StarterKitService;
    $kits = $service->getStarterKits();

    expect($kits)->toHaveCount(3)
        ->and($kits[0])->toBeInstanceOf(StarterKit::class)
        ->and($kits[0]->title)->toBe('Filakit v5')
        ->and($kits[0]->package)->toBe('jeffersongoncalves/filakitv5');
});

it('returns available versions', function () {
    config()->set('starterkits', [
        ['title' => 'Filakit v5', 'package' => 'jeffersongoncalves/filakitv5'],
        ['title' => 'Nativekit v5', 'package' => 'jeffersongoncalves/nativekitv5'],
        ['title' => 'Filakit v4', 'package' => 'jeffersongoncalves/filakitv4'],
        ['title' => 'Filakit v3', 'package' => 'jeffersongoncalves/filakit'],
    ]);

    $service = new StarterKitService;
    $versions = $service->getAvailableVersions();

    expect($versions)->toBe(['v5', 'v4', 'v3']);
});

it('returns numbered options filtered by version', function () {
    config()->set('starterkits', [
        ['title' => 'Filakit v5', 'package' => 'jeffersongoncalves/filakitv5'],
        ['title' => 'Nativekit v5', 'package' => 'jeffersongoncalves/nativekitv5'],
        ['title' => 'Filakit v4', 'package' => 'jeffersongoncalves/filakitv4'],
    ]);

    $service = new StarterKitService;
    $options = $service->getStarterKitOptions('v5');

    expect($options)->toBe([
        1 => 'Filakit v5',
        2 => 'Nativekit v5',
    ]);
});

it('finds starter kit by version and index', function () {
    config()->set('starterkits', [
        ['title' => 'Filakit v5', 'package' => 'jeffersongoncalves/filakitv5'],
        ['title' => 'Nativekit v5', 'package' => 'jeffersongoncalves/nativekitv5'],
        ['title' => 'Filakit v4', 'package' => 'jeffersongoncalves/filakitv4'],
    ]);

    $service = new StarterKitService;
    $kit = $service->findByIndex('v5', 2);

    expect($kit)->toBeInstanceOf(StarterKit::class)
        ->and($kit->title)->toBe('Nativekit v5')
        ->and($kit->package)->toBe('jeffersongoncalves/nativekitv5');
});

it('returns null for invalid index', function () {
    config()->set('starterkits', [
        ['title' => 'Filakit v5', 'package' => 'jeffersongoncalves/filakitv5'],
    ]);

    $service = new StarterKitService;
    $kit = $service->findByIndex('v5', 99);

    expect($kit)->toBeNull();
});

it('returns empty options for version with no kits', function () {
    config()->set('starterkits', [
        ['title' => 'Filakit v5', 'package' => 'jeffersongoncalves/filakitv5'],
    ]);

    $service = new StarterKitService;
    $options = $service->getStarterKitOptions('v3');

    expect($options)->toBeEmpty();
});

it('loads from default config file', function () {
    $service = new StarterKitService;
    $kits = $service->getStarterKits();

    expect($kits)->toBeArray()
        ->and($kits)->not->toBeEmpty()
        ->and($kits[0])->toBeInstanceOf(StarterKit::class)
        ->and($kits[0]->title)->toBe('Base Kit v5');
});
