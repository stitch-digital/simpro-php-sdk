<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceRetainage
{
    public function __construct(
        public ?int $jobId = null,
        public ?float $exTax = null,
        public ?float $incTax = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            jobId: isset($data['JobID']) ? (int) $data['JobID'] : null,
            exTax: isset($data['ExTax']) ? (float) $data['ExTax'] : null,
            incTax: isset($data['IncTax']) ? (float) $data['IncTax'] : null,
        );
    }
}
