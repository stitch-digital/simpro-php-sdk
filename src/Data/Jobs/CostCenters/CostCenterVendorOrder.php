<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters;

final readonly class CostCenterVendorOrder
{
    public function __construct(
        public int $id,
        public ?string $stage,
        public ?string $reference,
        public ?bool $showItemDueDate,
        public ?CostCenterVendorOrderTotals $totals,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            stage: $data['Stage'] ?? null,
            reference: $data['Reference'] ?? null,
            showItemDueDate: $data['ShowItemDueDate'] ?? null,
            totals: ! empty($data['Totals']) ? CostCenterVendorOrderTotals::fromArray($data['Totals']) : null,
        );
    }
}
