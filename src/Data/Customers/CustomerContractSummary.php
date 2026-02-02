<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use DateTimeImmutable;

/**
 * DTO for customer contract summary (embedded in Customer response).
 *
 * Based on swagger: Contracts array in GET /api/v1.0/companies/{companyID}/customers/companies/{customerID}
 */
final readonly class CustomerContractSummary
{
    public function __construct(
        public int $id,
        public string $name,
        public ?DateTimeImmutable $startDate,
        public ?DateTimeImmutable $endDate,
        public string $contractNo,
        public bool $expired,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            startDate: isset($data['StartDate']) ? new DateTimeImmutable($data['StartDate']) : null,
            endDate: isset($data['EndDate']) ? new DateTimeImmutable($data['EndDate']) : null,
            contractNo: $data['ContractNo'] ?? '',
            expired: $data['Expired'] ?? false,
        );
    }
}
