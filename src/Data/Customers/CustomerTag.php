<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

/**
 * Customer tag.
 */
final readonly class CustomerTag
{
    public function __construct(
        public int $id,
        public ?string $name = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
        );
    }
}
