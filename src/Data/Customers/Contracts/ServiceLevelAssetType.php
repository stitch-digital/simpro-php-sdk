<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for service level asset type details.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/serviceLevels/{serviceLevelID}/assetTypes/{assetTypeID}
 */
final readonly class ServiceLevelAssetType
{
    public function __construct(
        public Reference $assetType,
        public Prebuild $prebuild,
        public float $chargeRate,
        public float $estimatedTime,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            assetType: Reference::fromArray($data['AssetType']),
            prebuild: Prebuild::fromArray($data['Prebuild']),
            chargeRate: isset($data['ChargeRate']) ? (float) $data['ChargeRate'] : 0.0,
            estimatedTime: isset($data['EstimatedTime']) ? (float) $data['EstimatedTime'] : 0.0,
        );
    }
}
