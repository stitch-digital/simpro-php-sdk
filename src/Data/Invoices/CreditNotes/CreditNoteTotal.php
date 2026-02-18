<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices\CreditNotes;

final readonly class CreditNoteTotal
{
    public function __construct(
        public ?float $exTax = null,
        public ?float $tax = null,
        public ?float $incTax = null,
        public ?float $reverseChargeTax = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            exTax: isset($data['ExTax']) ? (float) $data['ExTax'] : null,
            tax: isset($data['Tax']) ? (float) $data['Tax'] : null,
            incTax: isset($data['IncTax']) ? (float) $data['IncTax'] : null,
            reverseChargeTax: isset($data['ReverseChargeTax']) ? (float) $data['ReverseChargeTax'] : null,
        );
    }
}
