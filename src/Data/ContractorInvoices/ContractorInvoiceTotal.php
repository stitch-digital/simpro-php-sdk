<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

final readonly class ContractorInvoiceTotal
{
    public function __construct(
        public ?float $exTax = null,
        public ?float $incTax = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            exTax: isset($data['ExTax']) ? (float) $data['ExTax'] : null,
            incTax: isset($data['IncTax']) ? (float) $data['IncTax'] : null,
        );
    }
}
