<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters;

use Simpro\PhpSdk\Simpro\Data\Common\Money;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class CostCenterListItem
{
    public function __construct(
        public int $id,
        public ?Reference $costCenter,
        public ?int $recurringId,
        public ?string $name,
        public ?int $displayOrder,
        public ?Money $total,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            recurringId: isset($data['RecurringID']) ? (int) $data['RecurringID'] : null,
            name: $data['Name'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? Money::fromArray($data['Total']) : null,
        );
    }
}
