<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for a single employee (detailed view).
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/employees/{employeeID}
 */
final readonly class Employee
{
    /**
     * @param  array<string>|null  $availability
     * @param  array<Reference>|null  $assignedCostCenters
     * @param  array<Reference>|null  $zones
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $position,
        public ?array $availability,
        public ?EmployeeAddress $address,
        public ?string $dateOfHire,
        public ?string $dateOfBirth,
        public ?EmployeePrimaryContact $primaryContact,
        public ?EmployeeEmergencyContact $emergencyContact,
        public ?EmployeeAccountSetup $accountSetup,
        public ?EmployeeUserProfile $userProfile,
        public ?DateTimeImmutable $dateCreated,
        public ?DateTimeImmutable $dateModified,
        public ?bool $archived,
        public ?array $assignedCostCenters,
        public ?array $zones,
        public ?Reference $defaultZone,
        public ?Reference $defaultCompany,
        public ?array $customFields,
        public ?string $maskedSSN,
        public ?EmployeeBanking $banking,
        public ?EmployeePayRates $payRates,
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
            address: ! empty($data['Address']) ? EmployeeAddress::fromArray($data['Address']) : null,
            dateOfHire: $data['DateOfHire'] ?? null,
            dateOfBirth: $data['DateOfBirth'] ?? null,
            primaryContact: ! empty($data['PrimaryContact']) ? EmployeePrimaryContact::fromArray($data['PrimaryContact']) : null,
            emergencyContact: ! empty($data['EmergencyContact']) ? EmployeeEmergencyContact::fromArray($data['EmergencyContact']) : null,
            accountSetup: ! empty($data['AccountSetup']) ? EmployeeAccountSetup::fromArray($data['AccountSetup']) : null,
            userProfile: ! empty($data['UserProfile']) ? EmployeeUserProfile::fromArray($data['UserProfile']) : null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            archived: $data['Archived'] ?? null,
            assignedCostCenters: isset($data['AssignedCostCenters']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['AssignedCostCenters']) : null,
            zones: isset($data['Zones']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['Zones']) : null,
            defaultZone: ! empty($data['DefaultZone']) ? Reference::fromArray($data['DefaultZone']) : null,
            defaultCompany: ! empty($data['DefaultCompany']) ? Reference::fromArray($data['DefaultCompany']) : null,
            customFields: isset($data['CustomFields']) ? array_map(fn (array $item) => CustomField::fromArray($item), $data['CustomFields']) : null,
            maskedSSN: $data['MaskedSSN'] ?? null,
            banking: ! empty($data['Banking']) ? EmployeeBanking::fromArray($data['Banking']) : null,
            payRates: ! empty($data['PayRates']) ? EmployeePayRates::fromArray($data['PayRates']) : null,
        );
    }
}
