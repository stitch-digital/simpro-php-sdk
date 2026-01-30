<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

/**
 * Customer response time configuration.
 */
final readonly class CustomerResponseTime
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $description = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
            description: $data['Description'] ?? null,
        );
    }
}
