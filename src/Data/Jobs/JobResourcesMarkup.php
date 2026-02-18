<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobResourcesMarkup
{
    public function __construct(
        public ?JobCostBreakdown $total,
        public ?JobCostBreakdown $labor,
        public ?JobCostBreakdown $plantAndEquipment,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            total: ! empty($data['Total']) ? JobCostBreakdown::fromArray($data['Total']) : null,
            labor: ! empty($data['Labor']) ? JobCostBreakdown::fromArray($data['Labor']) : null,
            plantAndEquipment: ! empty($data['PlantAndEquipment']) ? JobCostBreakdown::fromArray($data['PlantAndEquipment']) : null,
        );
    }
}
