<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobCostCenter
{
    /**
     * @param  array<string, mixed>|null  $items
     */
    public function __construct(
        public int $id,
        public ?string $name,
        public ?JobCostCenterReference $costCenter,
        public ?int $displayOrder,
        public ?JobCostCenterTotal $total,
        public ?JobCostCenterClaimed $claimed,
        public ?array $items,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            costCenter: ! empty($data['CostCenter']) ? JobCostCenterReference::fromArray($data['CostCenter']) : null,
            displayOrder: $data['DisplayOrder'] ?? null,
            total: ! empty($data['Total']) ? JobCostCenterTotal::fromArray($data['Total']) : null,
            claimed: ! empty($data['Claimed']) ? JobCostCenterClaimed::fromArray($data['Claimed']) : null,
            items: $data['Items'] ?? null,
        );
    }
}
