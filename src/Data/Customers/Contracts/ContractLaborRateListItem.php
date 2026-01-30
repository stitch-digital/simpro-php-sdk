<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for contract labor rate list items.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/laborRates/
 */
final readonly class ContractLaborRateListItem
{
    public function __construct(
        public Reference $laborRate,
        public bool $isDefault,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            laborRate: Reference::fromArray($data['LaborRate']),
            isDefault: $data['IsDefault'] ?? false,
        );
    }
}
