<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceRecurringInvoice
{
    public function __construct(
        public int $id,
        public ?string $description = null,
        public ?InvoiceTotal $total = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            description: $data['Description'] ?? null,
            total: ! empty($data['Total']) ? InvoiceTotal::fromArray($data['Total']) : null,
        );
    }
}
