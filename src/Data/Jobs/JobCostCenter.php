<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobCostCenter
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?JobCostCenterReference $costCenter,
        public ?int $displayOrder,
        public ?JobCostCenterTotal $total,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            costCenter: isset($data['CostCenter']) ? JobCostCenterReference::fromArray($data['CostCenter']) : null,
            displayOrder: $data['DisplayOrder'] ?? null,
            total: isset($data['Total']) ? JobCostCenterTotal::fromArray($data['Total']) : null,
        );
    }
}
