<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobSection
{
    /**
     * @param  array<JobCostCenter>|null  $costCenters
     */
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?int $displayOrder,
        public ?array $costCenters,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            description: $data['Description'] ?? null,
            displayOrder: $data['DisplayOrder'] ?? null,
            costCenters: isset($data['CostCenters']) ? array_map(
                fn (array $item) => JobCostCenter::fromArray($item),
                $data['CostCenters']
            ) : null,
        );
    }
}
