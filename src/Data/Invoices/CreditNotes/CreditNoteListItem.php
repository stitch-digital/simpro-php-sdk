<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices\CreditNotes;

use Simpro\PhpSdk\Simpro\Data\Invoices\InvoiceCustomer;

final readonly class CreditNoteListItem
{
    /**
     * @param  array<CreditNoteJob>  $jobs
     */
    public function __construct(
        public int $id,
        public ?InvoiceCustomer $customer = null,
        public array $jobs = [],
        public ?string $stage = null,
        public ?CreditNoteTotal $total = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            customer: ! empty($data['Customer']) ? InvoiceCustomer::fromArray($data['Customer']) : null,
            jobs: isset($data['Jobs']) ? array_map(
                fn (array $job) => CreditNoteJob::fromArray($job),
                $data['Jobs']
            ) : [],
            stage: $data['Stage'] ?? null,
            total: ! empty($data['Total']) ? CreditNoteTotal::fromArray($data['Total']) : null,
        );
    }
}
