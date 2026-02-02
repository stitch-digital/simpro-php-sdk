<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class CostCenterListItem
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?Reference $costCenter,
        public ?int $displayOrder,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            costCenter: isset($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
        );
    }
}
