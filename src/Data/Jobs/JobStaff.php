<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobStaff
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? '',
        );
    }
}
