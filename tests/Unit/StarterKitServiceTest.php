<?php

use App\DTOs\StarterKit;
use App\Services\StarterKitService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

function createMockClient(Response ...$responses): Client
{
    $mock = new MockHandler($responses);
    $handlerStack = HandlerStack::create($mock);

    return new Client(['handler' => $handlerStack]);
}

it('fetches starter kits from remote JSON', function () {
    $json = file_get_contents(base_path('tests/Fixtures/plugins.json'));

    $client = createMockClient(new Response(200, [], $json));
    $service = new StarterKitService($client);

    $kits = $service->fetchStarterKits();

    expect($kits)->toHaveCount(3)
        ->and($kits[0])->toBeInstanceOf(StarterKit::class)
        ->and($kits[0]->title)->toBe('Filakit v5')
        ->and($kits[0]->package)->toBe('jeffersongoncalves/filakitv5')
        ->and($kits[1]->title)->toBe('Nativekit v5')
        ->and($kits[2]->title)->toBe('Filakit v4');
});

it('returns starter kit options as key-value pairs', function () {
    $json = file_get_contents(base_path('tests/Fixtures/plugins.json'));

    $client = createMockClient(new Response(200, [], $json));
    $service = new StarterKitService($client);

    $options = $service->getStarterKitOptions();

    expect($options)->toBe([
        'jeffersongoncalves/filakitv5' => 'Filakit v5',
        'jeffersongoncalves/nativekitv5' => 'Nativekit v5',
        'jeffersongoncalves/filakitv4' => 'Filakit v4',
    ]);
});

it('throws exception for invalid JSON structure', function () {
    $client = createMockClient(new Response(200, [], '{"invalid": true}'));
    $service = new StarterKitService($client);

    $service->fetchStarterKits();
})->throws(RuntimeException::class, 'Invalid JSON structure: missing "startkit" key.');

it('throws exception on HTTP error', function () {
    $client = createMockClient(new Response(500, [], 'Server Error'));
    $service = new StarterKitService($client);

    $service->fetchStarterKits();
})->throws(RuntimeException::class, 'Failed to fetch starter kits');
