<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceTotal
{
    public function __construct(
        public float $exTax,
        public float $tax,
        public float $incTax,
        public ?float $reverseChargeTax = null,
        public ?float $amountApplied = null,
        public ?float $balanceDue = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            exTax: (float) ($data['ExTax'] ?? 0),
            tax: (float) ($data['Tax'] ?? 0),
            incTax: (float) ($data['IncTax'] ?? 0),
            reverseChargeTax: isset($data['ReverseChargeTax']) ? (float) $data['ReverseChargeTax'] : null,
            amountApplied: isset($data['AmountApplied']) ? (float) $data['AmountApplied'] : null,
            balanceDue: isset($data['BalanceDue']) ? (float) $data['BalanceDue'] : null,
        );
    }
}
