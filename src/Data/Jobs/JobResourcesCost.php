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
            total: isset($data['Total']) ? JobCostBreakdown::fromArray($data['Total']) : null,
            labor: isset($data['Labor']) ? JobCostBreakdown::fromArray($data['Labor']) : null,
            laborHours: isset($data['LaborHours']) ? JobCostBreakdown::fromArray($data['LaborHours']) : null,
            plantAndEquipment: isset($data['PlantAndEquipment']) ? JobCostBreakdown::fromArray($data['PlantAndEquipment']) : null,
            plantAndEquipmentHours: isset($data['PlantAndEquipmentHours']) ? JobCostBreakdown::fromArray($data['PlantAndEquipmentHours']) : null,
            commission: isset($data['Commission']) ? JobCostBreakdown::fromArray($data['Commission']) : null,
            overhead: isset($data['Overhead']) ? JobCostBreakdown::fromArray($data['Overhead']) : null,
        );
    }
}
