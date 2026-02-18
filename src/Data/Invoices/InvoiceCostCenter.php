<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class InvoiceCostCenter
{
    /**
     * @param  array<InvoiceCostCenterItem>  $items
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?int $jobId = null,
        public ?int $recurringInvoiceId = null,
        public ?int $sectionId = null,
        public ?Reference $costCenter = null,
        public ?InvoiceCostCenterTotal $total = null,
        public ?InvoiceCostCenterClaim $claim = null,
        public array $items = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            jobId: isset($data['JobID']) ? (int) $data['JobID'] : null,
            recurringInvoiceId: isset($data['RecurringInvoiceID']) ? (int) $data['RecurringInvoiceID'] : null,
            sectionId: isset($data['SectionID']) ? (int) $data['SectionID'] : null,
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            total: ! empty($data['Total']) ? InvoiceCostCenterTotal::fromArray($data['Total']) : null,
            claim: ! empty($data['Claim']) ? InvoiceCostCenterClaim::fromArray($data['Claim']) : null,
            items: isset($data['Items']) ? array_map(
                fn (array $item) => InvoiceCostCenterItem::fromArray($item),
                $data['Items']
            ) : [],
        );
    }
}
