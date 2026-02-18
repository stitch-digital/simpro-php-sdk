<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for contract service level with nested details.
 *
 * This is the detailed format returned in the Contract GET response,
 * containing full ServiceLevel and AssetType details.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}
 */
final readonly class ContractServiceLevel
{
    public function __construct(
        public ?ServiceLevelDetail $serviceLevel,
        public ?Reference $assetType,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceLevel: ! empty($data['ServiceLevel']) ? ServiceLevelDetail::fromArray($data['ServiceLevel']) : null,
            assetType: ! empty($data['AssetType']) ? Reference::fromArray($data['AssetType']) : null,
        );
    }
}
