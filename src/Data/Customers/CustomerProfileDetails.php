<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for customer profile details.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/individuals/{customerID}
 */
final readonly class CustomerProfileDetails
{
    public function __construct(
        public string $notes,
        public ?Reference $customerProfile,
        public ?Reference $customerGroup,
        public ?Reference $accountManager,
        public ?CustomerCurrency $currency,
        public ?Reference $serviceJobCostCenter,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            notes: $data['Notes'] ?? '',
            customerProfile: ! empty($data['CustomerProfile']) ? Reference::fromArray($data['CustomerProfile']) : null,
            customerGroup: ! empty($data['CustomerGroup']) ? Reference::fromArray($data['CustomerGroup']) : null,
            accountManager: ! empty($data['AccountManager']) ? Reference::fromArray($data['AccountManager']) : null,
            currency: ! empty($data['Currency']) ? CustomerCurrency::fromArray($data['Currency']) : null,
            serviceJobCostCenter: ! empty($data['ServiceJobCostCenter']) ? Reference::fromArray($data['ServiceJobCostCenter']) : null,
        );
    }
}
