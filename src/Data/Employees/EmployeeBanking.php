<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

/**
 * DTO for employee banking information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/employees/{employeeID}
 */
final readonly class EmployeeBanking
{
    public function __construct(
        public ?string $accountName,
        public ?string $routingNo,
        public ?string $accountNo,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accountName: $data['AccountName'] ?? null,
            routingNo: $data['RoutingNo'] ?? null,
            accountNo: $data['AccountNo'] ?? null,
        );
    }
}
