<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;

/**
 * DTO for a single contract (detailed view).
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}
 */
final readonly class Contract
{
    /**
     * @param  array<CustomField>|null  $customFields
     * @param  array<ContractServiceLevel>|null  $serviceLevels
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $startDate,
        public ?string $endDate,
        public string $contractNo,
        public float $value,
        public string $notes,
        public string $email,
        public bool $archived,
        public bool $expired,
        public ?PricingTier $pricingTier,
        public float $markup,
        public ?ContractRates $rates,
        public ?array $customFields,
        public ?array $serviceLevels,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? '',
            startDate: $data['StartDate'] ?? null,
            endDate: $data['EndDate'] ?? null,
            contractNo: $data['ContractNo'] ?? '',
            value: isset($data['Value']) ? (float) $data['Value'] : 0.0,
            notes: $data['Notes'] ?? '',
            email: $data['Email'] ?? '',
            archived: $data['Archived'] ?? false,
            expired: $data['Expired'] ?? false,
            pricingTier: ! empty($data['PricingTier']) ? PricingTier::fromArray($data['PricingTier']) : null,
            markup: isset($data['Markup']) ? (float) $data['Markup'] : 0.0,
            rates: ! empty($data['Rates']) ? ContractRates::fromArray($data['Rates']) : null,
            customFields: isset($data['CustomFields']) ? array_map(fn (array $item) => CustomField::fromArray($item), $data['CustomFields']) : null,
            serviceLevels: isset($data['ServiceLevels']) ? array_map(fn (array $item) => ContractServiceLevel::fromArray($item), $data['ServiceLevels']) : null,
        );
    }
}
