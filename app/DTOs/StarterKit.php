<?php

namespace App\DTOs;

class StarterKit
{
    public function __construct(
        public readonly string $title,
        public readonly string $package,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            package: $data['package'],
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'package' => $this->package,
        ];
    }

    public function detectVersion(): ?string
    {
        if (preg_match('/v(\d+)$/i', $this->package, $m)) {
            return 'v'.$m[1];
        }

        if (preg_match('/v(\d+)/i', $this->title, $m)) {
            return 'v'.$m[1];
        }

        // v3 kits don't have version suffix in package name
        if (preg_match('/v(\d+)/i', $this->title, $m)) {
            return 'v'.$m[1];
        }

        return null;
    }
}
