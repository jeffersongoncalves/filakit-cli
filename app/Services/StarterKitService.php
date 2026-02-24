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
     * @return array<string, string>
     */
    public function getStarterKitOptions(): array
    {
        $kits = $this->getStarterKits();

        $options = [];
        foreach ($kits as $kit) {
            $options[$kit->package] = $kit->title;
        }

        return $options;
    }
}
