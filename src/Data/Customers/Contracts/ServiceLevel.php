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
        public ?Reference $serviceLevel,
        public ?Reference $assetType,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceLevel: ! empty($data['ServiceLevel']) ? Reference::fromArray($data['ServiceLevel']) : null,
            assetType: ! empty($data['AssetType']) ? Reference::fromArray($data['AssetType']) : null,
        );
    }
}
