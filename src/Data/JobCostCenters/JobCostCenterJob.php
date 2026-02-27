<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\JobCostCenters;

final readonly class JobCostCenterJob
{
    public function __construct(
        public int $id,
        public ?string $type = null,
        public ?string $name = null,
        public ?string $stage = null,
        public ?string $status = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            type: $data['Type'] ?? null,
            name: $data['Name'] ?? null,
            stage: $data['Stage'] ?? null,
            status: $data['Status'] ?? null,
        );
    }
}
