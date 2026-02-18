<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceCostCenterItemDetail
{
    public function __construct(
        public int $id,
        public ?string $partNo = null,
        public ?string $name = null,
        public ?string $type = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            partNo: $data['PartNo'] ?? null,
            name: $data['Name'] ?? null,
            type: $data['Type'] ?? null,
        );
    }
}
