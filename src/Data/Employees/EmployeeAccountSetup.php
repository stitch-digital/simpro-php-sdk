<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for employee account setup information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/employees/{employeeID}
 */
final readonly class EmployeeAccountSetup
{
    public function __construct(
        public ?string $username,
        public ?bool $isMobility,
        public ?Reference $securityGroup,
        public ?Reference $mobileSecurityGroup,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            username: $data['Username'] ?? null,
            isMobility: $data['IsMobility'] ?? null,
            securityGroup: isset($data['SecurityGroup']) ? Reference::fromArray($data['SecurityGroup']) : null,
            mobileSecurityGroup: isset($data['MobileSecurityGroup']) ? Reference::fromArray($data['MobileSecurityGroup']) : null,
        );
    }
}
