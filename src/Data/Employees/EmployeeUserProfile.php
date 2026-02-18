<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for employee user profile information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/employees/{employeeID}
 */
final readonly class EmployeeUserProfile
{
    public function __construct(
        public ?bool $isSalesperson,
        public ?bool $isProjectManager,
        public ?Reference $storageDevice,
        public ?string $preferredLanguage,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isSalesperson: $data['IsSalesperson'] ?? null,
            isProjectManager: $data['IsProjectManager'] ?? null,
            storageDevice: ! empty($data['StorageDevice']) ? Reference::fromArray($data['StorageDevice']) : null,
            preferredLanguage: $data['PreferredLanguage'] ?? null,
        );
    }
}
