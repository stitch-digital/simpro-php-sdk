<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceListJob
{
    public function __construct(
        public int $id,
        public ?string $description,
        public ?InvoiceTotal $total,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            description: $data['Description'] ?? null,
            total: isset($data['Total']) ? InvoiceTotal::fromArray($data['Total']) : null,
        );
    }
}
