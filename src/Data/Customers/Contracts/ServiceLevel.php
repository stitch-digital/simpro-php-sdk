<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for service level list items.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/serviceLevels/
 */
final readonly class ServiceLevel
{
    public function __construct(
        public Reference $serviceLevel,
        public Reference $assetType,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceLevel: Reference::fromArray($data['ServiceLevel']),
            assetType: Reference::fromArray($data['AssetType']),
        );
    }
}
