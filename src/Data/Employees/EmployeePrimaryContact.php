<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

/**
 * DTO for employee primary contact information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/employees/{employeeID}
 */
final readonly class EmployeePrimaryContact
{
    public function __construct(
        public ?string $email,
        public ?string $secondaryEmail,
        public ?string $workPhone,
        public ?string $extension,
        public ?string $cellPhone,
        public ?string $fax,
        public ?string $preferredNotificationMethod,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['Email'] ?? null,
            secondaryEmail: $data['SecondaryEmail'] ?? null,
            workPhone: $data['WorkPhone'] ?? null,
            extension: $data['Extension'] ?? null,
            cellPhone: $data['CellPhone'] ?? null,
            fax: $data['Fax'] ?? null,
            preferredNotificationMethod: $data['PreferredNotificationMethod'] ?? null,
        );
    }
}
