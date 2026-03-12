<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\CustomerAssets;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class CustomerAssetLastTest
{
    public function __construct(
        public ?string $result = null,
        public ?string $date = null,
        public ?Reference $serviceLevel = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            result: $data['Result'] ?? null,
            date: $data['Date'] ?? null,
            serviceLevel: isset($data['ServiceLevel']) && is_array($data['ServiceLevel'])
                ? Reference::fromArray($data['ServiceLevel'])
                : null,
        );
    }
}
