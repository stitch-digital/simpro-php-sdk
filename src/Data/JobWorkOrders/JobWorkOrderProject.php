<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\JobWorkOrders;

final readonly class JobWorkOrderProject
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?int $sectionId = null,
        public ?int $costCenterId = null,
        public ?string $costCenterName = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? null,
            sectionId: isset($data['SectionID']) ? (int) $data['SectionID'] : null,
            costCenterId: isset($data['CostCenterID']) ? (int) $data['CostCenterID'] : null,
            costCenterName: $data['CostCenterName'] ?? null,
        );
    }
}
