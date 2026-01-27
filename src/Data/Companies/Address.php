<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Companies;

final readonly class Address
{
    public function __construct(
        public string $line1,
        public string $line2,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            line1: $data['Line1'] ?? '',
            line2: $data['Line2'] ?? '',
        );
    }
}
