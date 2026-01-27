<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Companies;

final readonly class DefaultCostCenter
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'],
        );
    }
}
