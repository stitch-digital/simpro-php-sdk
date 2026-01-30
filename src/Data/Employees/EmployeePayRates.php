<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

/**
 * DTO for employee pay rates information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/employees/{employeeID}
 */
final readonly class EmployeePayRates
{
    public function __construct(
        public ?float $payRate,
        public ?float $employmentCost,
        public ?float $overhead,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            payRate: isset($data['PayRate']) ? (float) $data['PayRate'] : null,
            employmentCost: isset($data['EmploymentCost']) ? (float) $data['EmploymentCost'] : null,
            overhead: isset($data['Overhead']) ? (float) $data['Overhead'] : null,
        );
    }
}
