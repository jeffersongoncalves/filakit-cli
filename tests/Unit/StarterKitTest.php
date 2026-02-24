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

it('has readonly properties', function () {
    $kit = new StarterKit(
        title: 'Filakit v5',
        package: 'jeffersongoncalves/filakitv5',
    );

    expect($kit)->toBeInstanceOf(StarterKit::class)
        ->and($kit->title)->toBeString()
        ->and($kit->package)->toBeString();
});
