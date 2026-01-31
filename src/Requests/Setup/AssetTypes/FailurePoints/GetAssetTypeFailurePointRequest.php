<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\FailurePoints;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeFailurePoint;

/**
 * Get a specific AssetTypeFailurePoint.
 */
final class GetAssetTypeFailurePointRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $serviceLevelId,
        private readonly int|string $failurePointId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/serviceLevels/{$this->serviceLevelId}/failurePoints/{$this->failurePointId}";
    }

    public function createDtoFromResponse(Response $response): AssetTypeFailurePoint
    {
        return AssetTypeFailurePoint::fromResponse($response);
    }
}
