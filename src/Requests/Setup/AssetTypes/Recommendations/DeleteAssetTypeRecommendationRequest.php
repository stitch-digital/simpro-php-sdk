<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Recommendations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a AssetTypeRecommendation.
 */
final class DeleteAssetTypeRecommendationRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $serviceLevelId,
        private readonly int|string $failurePointId,
        private readonly int|string $recommendationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/serviceLevels/{$this->serviceLevelId}/failurePoints/{$this->failurePointId}/recommendations/{$this->recommendationId}";
    }
}
