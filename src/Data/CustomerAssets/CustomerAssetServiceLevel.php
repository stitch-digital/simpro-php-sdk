<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\CustomerAssets;

final readonly class CustomerAssetServiceLevel
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $serviceDate = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
            serviceDate: $data['ServiceDate'] ?? null,
        );
    }
}
