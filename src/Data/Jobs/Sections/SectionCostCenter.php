<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Sections;

final readonly class SectionCostCenter
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?int $costCenterId,
        public ?string $costCenterName,
        public ?int $displayOrder,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            costCenterId: isset($data['CostCenter']['ID']) ? (int) $data['CostCenter']['ID'] : null,
            costCenterName: $data['CostCenter']['Name'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
        );
    }
}
