<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

/**
 * DTO for employee emergency contact information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/employees/{employeeID}
 */
final readonly class EmployeeEmergencyContact
{
    public function __construct(
        public ?string $name,
        public ?string $relationship,
        public ?string $workPhone,
        public ?string $cellPhone,
        public ?string $altPhone,
        public ?string $address,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['Name'] ?? null,
            relationship: $data['Relationship'] ?? null,
            workPhone: $data['WorkPhone'] ?? null,
            cellPhone: $data['CellPhone'] ?? null,
            altPhone: $data['AltPhone'] ?? null,
            address: $data['Address'] ?? null,
        );
    }
}
