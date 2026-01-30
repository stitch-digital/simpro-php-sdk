<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

/**
 * DTO for prebuild information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/serviceLevels/{serviceLevelID}/assetTypes/{assetTypeID}
 */
final readonly class Prebuild
{
    public function __construct(
        public int $id,
        public string $partNo,
        public string $name,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            partNo: $data['PartNo'] ?? '',
            name: $data['Name'] ?? '',
        );
    }
}
