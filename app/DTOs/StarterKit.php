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
}
