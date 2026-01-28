<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceListItem
{
    public function __construct(
        public int $id,
        public ?string $invoiceNo,
        public ?string $status,
        public ?string $customer,
        public ?string $customerId,
        public ?string $dateIssued,
        public ?string $dateDue,
        public ?float $total,
        public ?float $amountDue,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            invoiceNo: $data['InvoiceNo'] ?? null,
            status: $data['Status'] ?? null,
            customer: $data['Customer'] ?? null,
            customerId: $data['CustomerID'] ?? null,
            dateIssued: $data['DateIssued'] ?? null,
            dateDue: $data['DateDue'] ?? null,
            total: isset($data['Total']) ? (float) $data['Total'] : null,
            amountDue: isset($data['AmountDue']) ? (float) $data['AmountDue'] : null,
        );
    }
}
