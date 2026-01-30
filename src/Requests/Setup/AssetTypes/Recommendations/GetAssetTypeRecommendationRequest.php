<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Recommendations;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeRecommendation;

/**
 * Get a specific AssetTypeRecommendation.
 */
final class GetAssetTypeRecommendationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $serviceLevelId,
        private readonly int|string $failurePointId,
        private readonly int|string $recommendationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/serviceLevels/{$this->serviceLevelId}/failurePoints/{$this->failurePointId}/recommendations/{$this->recommendationId}";
    }

    public function createDtoFromResponse(Response $response): AssetTypeRecommendation
    {
        return AssetTypeRecommendation::fromResponse($response);
    }
}
