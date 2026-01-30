<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

/**
 * DTO for contract list items.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/
 */
final readonly class ContractListItem
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $startDate,
        public ?string $endDate,
        public string $contractNo,
        public bool $expired,
    ) {}

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
            expired: $data['Expired'] ?? false,
        );
    }
}
