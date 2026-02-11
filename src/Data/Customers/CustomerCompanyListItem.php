<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

final readonly class CustomerCompanyListItem
{
    public function __construct(
        public int $id,
        public string $companyName,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            companyName: $data['CompanyName'] ?? '',
        );
    }
}
