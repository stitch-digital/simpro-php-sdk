<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceListItem
{
    /**
     * @param  array<InvoiceListJob>  $jobs
     */
    public function __construct(
        public int $id,
        public string $type,
        public ?InvoiceListCustomer $customer,
        public array $jobs,
        public ?InvoiceTotal $total,
        public bool $isPaid,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? '',
            customer: isset($data['Customer']) ? InvoiceListCustomer::fromArray($data['Customer']) : null,
            jobs: isset($data['Jobs']) ? array_map(
                fn (array $job) => InvoiceListJob::fromArray($job),
                $data['Jobs']
            ) : [],
            total: isset($data['Total']) ? InvoiceTotal::fromArray($data['Total']) : null,
            isPaid: $data['IsPaid'] ?? false,
        );
    }
}
