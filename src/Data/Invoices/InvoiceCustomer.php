<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceCustomer
{
    public function __construct(
        public int $id,
        public string $companyName,
        public string $type,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            companyName: $data['CompanyName'] ?? '',
            type: $data['Type'] ?? '',
        );
    }
}
