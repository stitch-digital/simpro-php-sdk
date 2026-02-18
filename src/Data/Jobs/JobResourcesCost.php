<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobResourcesCost
{
    public function __construct(
        public ?JobCostBreakdown $total,
        public ?JobCostBreakdown $labor,
        public ?JobCostBreakdown $laborHours,
        public ?JobCostBreakdown $plantAndEquipment,
        public ?JobCostBreakdown $plantAndEquipmentHours,
        public ?JobCostBreakdown $commission,
        public ?JobCostBreakdown $overhead,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            total: ! empty($data['Total']) ? JobCostBreakdown::fromArray($data['Total']) : null,
            labor: ! empty($data['Labor']) ? JobCostBreakdown::fromArray($data['Labor']) : null,
            laborHours: ! empty($data['LaborHours']) ? JobCostBreakdown::fromArray($data['LaborHours']) : null,
            plantAndEquipment: ! empty($data['PlantAndEquipment']) ? JobCostBreakdown::fromArray($data['PlantAndEquipment']) : null,
            plantAndEquipmentHours: ! empty($data['PlantAndEquipmentHours']) ? JobCostBreakdown::fromArray($data['PlantAndEquipmentHours']) : null,
            commission: ! empty($data['Commission']) ? JobCostBreakdown::fromArray($data['Commission']) : null,
            overhead: ! empty($data['Overhead']) ? JobCostBreakdown::fromArray($data['Overhead']) : null,
        );
    }
}
