<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Contractors;

final readonly class ContractorRates
{
    public function __construct(
        public ?float $payRate,
        public ?float $employmentCost,
        public ?float $overhead,
        public ?ContractorTaxCode $taxCode,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            payRate: isset($data['PayRate']) ? (float) $data['PayRate'] : null,
            employmentCost: isset($data['EmploymentCost']) ? (float) $data['EmploymentCost'] : null,
            overhead: isset($data['Overhead']) ? (float) $data['Overhead'] : null,
            taxCode: ! empty($data['TaxCode']) ? ContractorTaxCode::fromArray($data['TaxCode']) : null,
        );
    }
}
