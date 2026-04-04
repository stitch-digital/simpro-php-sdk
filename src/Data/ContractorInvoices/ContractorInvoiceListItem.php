<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

final readonly class ContractorInvoiceListItem
{
    /**
     * @param  array<int>  $contractorJobs
     */
    public function __construct(
        public int $id,
        public array $contractorJobs = [],
        public ?string $invoiceNo = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            contractorJobs: array_map('intval', $data['ContractorJobs'] ?? []),
            invoiceNo: $data['InvoiceNo'] ?? null,
        );
    }
}
