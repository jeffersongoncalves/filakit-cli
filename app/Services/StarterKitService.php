<?php

namespace App\Services;

use App\DTOs\StarterKit;

class StarterKitService
{
    /**
     * @return array<int, StarterKit>
     */
    public function getStarterKits(): array
    {
        $items = config('starterkits', []);

        return array_map(
            fn (array $item) => StarterKit::fromArray($item),
            $items
        );
    }

    /**
     * @return string[]
     */
    public function getAvailableVersions(): array
    {
        $versions = [];

        foreach ($this->getStarterKits() as $kit) {
            $version = $kit->detectVersion();
            if ($version && ! in_array($version, $versions, true)) {
                $versions[] = $version;
            }
        }

        return $versions;
    }

    /**
     * @return array<int, string>
     */
    public function getStarterKitOptions(string $version): array
    {
        $kits = $this->getStarterKitsByVersion($version);

        $options = [];
        foreach ($kits as $index => $kit) {
            $options[$index + 1] = $kit->title;
        }

        return $options;
    }

    /**
     * @return array<int, StarterKit>
     */
    public function getStarterKitsByVersion(string $version): array
    {
        return array_values(array_filter(
            $this->getStarterKits(),
            fn (StarterKit $kit) => $kit->detectVersion() === $version
        ));
    }

    public function findByIndex(string $version, int $index): ?StarterKit
    {
        $kits = $this->getStarterKitsByVersion($version);

        return $kits[$index - 1] ?? null;
    }
}
