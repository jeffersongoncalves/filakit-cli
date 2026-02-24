<?php

use App\DTOs\StarterKit;

it('creates a starter kit from array', function () {
    $kit = StarterKit::fromArray([
        'title' => 'Filakit v5',
        'package' => 'jeffersongoncalves/filakitv5',
    ]);

    expect($kit->title)->toBe('Filakit v5')
        ->and($kit->package)->toBe('jeffersongoncalves/filakitv5');
});

it('converts a starter kit to array', function () {
    $kit = new StarterKit(
        title: 'Nativekit v5',
        package: 'jeffersongoncalves/nativekitv5',
    );

    expect($kit->toArray())->toBe([
        'title' => 'Nativekit v5',
        'package' => 'jeffersongoncalves/nativekitv5',
    ]);
});

it('detects version from package name with suffix', function () {
    $kit = new StarterKit(title: 'Filakit v5', package: 'jeffersongoncalves/filakitv5');
    expect($kit->detectVersion())->toBe('v5');

    $kit = new StarterKit(title: 'Filakit v4', package: 'jeffersongoncalves/filakitv4');
    expect($kit->detectVersion())->toBe('v4');
});

it('detects version from title for v3 kits', function () {
    $kit = new StarterKit(title: 'Filakit v3', package: 'jeffersongoncalves/filakit');
    expect($kit->detectVersion())->toBe('v3');

    $kit = new StarterKit(title: 'Teamkit v3', package: 'jeffersongoncalves/teamkit');
    expect($kit->detectVersion())->toBe('v3');
});

it('returns null when version cannot be detected', function () {
    $kit = new StarterKit(title: 'Custom Kit', package: 'vendor/custom-kit');
    expect($kit->detectVersion())->toBeNull();
});
