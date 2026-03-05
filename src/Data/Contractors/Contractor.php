<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Contractors;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for a single contractor (detailed view).
 *
 * Based on: GET /api/v1.0/companies/{companyID}/contractors/{contractorID}
 */
final readonly class Contractor
{
    /**
     * @param  array<string>|null  $availability
     * @param  array<Reference>|null  $assignedCostCenters
     * @param  array<Reference>|null  $zones
     * @param  array<Reference>|null  $licences
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $position,
        public ?array $availability,
        public ?ContractorAddress $address,
        public ?string $dateOfHire,
        public ?string $dateOfBirth,
        public ?ContractorPrimaryContact $primaryContact,
        public ?ContractorEmergencyContact $emergencyContact,
        public ?ContractorAccountSetup $accountSetup,
        public ?ContractorUserProfile $userProfile,
        public ?DateTimeImmutable $dateCreated,
        public ?DateTimeImmutable $dateModified,
        public ?bool $archived,
        public ?array $assignedCostCenters,
        public ?array $zones,
        public ?Reference $defaultZone,
        public ?Reference $defaultCompany,
        public ?array $licences,
        public ?array $customFields,
        public ?string $ein,
        public ?string $maskedSSN,
        public ?string $companyNumber,
        public ?string $contactName,
        public ?string $currency,
        public ?ContractorBanking $banking,
        public ?ContractorRates $rates,
        public ?int $displayOrder,
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
            position: $data['Position'] ?? null,
            availability: $data['Availability'] ?? null,
            address: ! empty($data['Address']) ? ContractorAddress::fromArray($data['Address']) : null,
            dateOfHire: $data['DateOfHire'] ?? null,
            dateOfBirth: $data['DateOfBirth'] ?? null,
            primaryContact: ! empty($data['PrimaryContact']) ? ContractorPrimaryContact::fromArray($data['PrimaryContact']) : null,
            emergencyContact: ! empty($data['EmergencyContact']) ? ContractorEmergencyContact::fromArray($data['EmergencyContact']) : null,
            accountSetup: ! empty($data['AccountSetup']) ? ContractorAccountSetup::fromArray($data['AccountSetup']) : null,
            userProfile: ! empty($data['UserProfile']) ? ContractorUserProfile::fromArray($data['UserProfile']) : null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            archived: $data['Archived'] ?? null,
            assignedCostCenters: isset($data['AssignedCostCenters']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['AssignedCostCenters']) : null,
            zones: isset($data['Zones']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['Zones']) : null,
            defaultZone: ! empty($data['DefaultZone']) ? Reference::fromArray($data['DefaultZone']) : null,
            defaultCompany: ! empty($data['DefaultCompany']) ? Reference::fromArray($data['DefaultCompany']) : null,
            licences: isset($data['Licences']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['Licences']) : null,
            customFields: isset($data['CustomFields']) ? array_map(fn (array $item) => CustomField::fromArray($item), $data['CustomFields']) : null,
            ein: $data['EIN'] ?? null,
            maskedSSN: $data['MaskedSSN'] ?? null,
            companyNumber: $data['CompanyNumber'] ?? null,
            contactName: $data['ContactName'] ?? null,
            currency: $data['Currency'] ?? null,
            banking: ! empty($data['Banking']) ? ContractorBanking::fromArray($data['Banking']) : null,
            rates: ! empty($data['Rates']) ? ContractorRates::fromArray($data['Rates']) : null,
            displayOrder: $data['DisplayOrder'] ?? null,
        );
    }
}
