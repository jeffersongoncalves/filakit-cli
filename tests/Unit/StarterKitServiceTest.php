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
        ->and($kits[0]->package)->toBe('jeffersongoncalves/filakitv5')
        ->and($kits[1]->title)->toBe('Nativekit v5')
        ->and($kits[2]->title)->toBe('Filakit v4');
});

it('returns starter kit options as key-value pairs', function () {
    config()->set('starterkits', [
        ['title' => 'Filakit v5', 'package' => 'jeffersongoncalves/filakitv5'],
        ['title' => 'Nativekit v5', 'package' => 'jeffersongoncalves/nativekitv5'],
        ['title' => 'Filakit v4', 'package' => 'jeffersongoncalves/filakitv4'],
    ]);

    $service = new StarterKitService;
    $options = $service->getStarterKitOptions();

    expect($options)->toBe([
        'jeffersongoncalves/filakitv5' => 'Filakit v5',
        'jeffersongoncalves/nativekitv5' => 'Nativekit v5',
        'jeffersongoncalves/filakitv4' => 'Filakit v4',
    ]);
});

it('returns empty array when config is empty', function () {
    config()->set('starterkits', []);

    $service = new StarterKitService;
    $kits = $service->getStarterKits();

    expect($kits)->toBeEmpty();
});

it('returns empty options when config is empty', function () {
    config()->set('starterkits', []);

    $service = new StarterKitService;
    $options = $service->getStarterKitOptions();

    expect($options)->toBeEmpty();
});

it('loads from default config file', function () {
    $service = new StarterKitService;
    $kits = $service->getStarterKits();

    expect($kits)->toBeArray()
        ->and($kits)->not->toBeEmpty()
        ->and($kits[0])->toBeInstanceOf(StarterKit::class)
        ->and($kits[0]->title)->toBe('Filakit v5');
});
