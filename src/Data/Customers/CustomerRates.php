<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for customer rates information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/individuals/{customerID}
 */
final readonly class CustomerRates
{
    public function __construct(
        public ?TaxCode $partTaxCode,
        public ?TaxCode $labourTaxCode,
        public float $discountFee,
        public bool $alwaysDeductCIS,
        public ?Reference $serviceFee,
        public ?CustomerMaterial $material,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            partTaxCode: ! empty($data['PartTaxCode']) ? TaxCode::fromArray($data['PartTaxCode']) : null,
            labourTaxCode: ! empty($data['LabourTaxCode']) ? TaxCode::fromArray($data['LabourTaxCode']) : null,
            discountFee: isset($data['DiscountFee']) ? (float) $data['DiscountFee'] : 0.0,
            alwaysDeductCIS: $data['AlwaysDeductCIS'] ?? false,
            serviceFee: ! empty($data['ServiceFee']) ? Reference::fromArray($data['ServiceFee']) : null,
            material: ! empty($data['Material']) ? CustomerMaterial::fromArray($data['Material']) : null,
        );
    }
}
