<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Reports;

/**
 * Budget breakdown DTO containing materials, resources, and resource hours.
 */
final readonly class BudgetBreakdown
{
    public function __construct(
        public float $materials,
        public float $resources,
        public float $resourceHours,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            materials: (float) ($data['Materials'] ?? 0),
            resources: (float) ($data['Resources'] ?? 0),
            resourceHours: (float) ($data['ResourceHours'] ?? 0),
        );
    }
}
