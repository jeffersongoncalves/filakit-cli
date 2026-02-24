<?php

namespace App\Services;

use App\DTOs\StarterKit;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

class StarterKitService
{
    protected const PLUGINS_URL = 'https://raw.githubusercontent.com/jeffersongoncalves/jeffersongoncalves/refs/heads/master/plugins.json';

    protected Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client(['timeout' => 30]);
    }

    /**
     * @return array<int, StarterKit>
     */
    public function fetchStarterKits(): array
    {
        try {
            $response = $this->client->get(self::PLUGINS_URL);
            $data = json_decode($response->getBody()->getContents(), true);

            if (! is_array($data) || ! isset($data['startkit'])) {
                throw new RuntimeException('Invalid JSON structure: missing "startkit" key.');
            }

            return array_map(
                fn (array $item) => StarterKit::fromArray($item),
                $data['startkit']
            );
        } catch (GuzzleException $e) {
            throw new RuntimeException("Failed to fetch starter kits: {$e->getMessage()}");
        }
    }

    /**
     * @return array<string, string>
     */
    public function getStarterKitOptions(): array
    {
        $kits = $this->fetchStarterKits();

        $options = [];
        foreach ($kits as $kit) {
            $options[$kit->package] = $kit->title;
        }

        return $options;
    }
}
