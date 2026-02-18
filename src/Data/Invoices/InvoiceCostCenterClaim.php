<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceCostCenterClaim
{
    public function __construct(
        public ?float $exTax = null,
        public ?float $incTax = null,
        public ?float $percent = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            exTax: isset($data['ExTax']) ? (float) $data['ExTax'] : null,
            incTax: isset($data['IncTax']) ? (float) $data['IncTax'] : null,
            percent: isset($data['Percent']) ? (float) $data['Percent'] : null,
        );
    }
}
