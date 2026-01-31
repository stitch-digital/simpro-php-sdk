<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Recommendations;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeRecommendationListItem;

/**
 * List all AssetTypeRecommendations.
 */
final class ListAssetTypeRecommendationsRequest extends Request implements Paginatable
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
        return "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/serviceLevels/{$this->serviceLevelId}/failurePoints/{$this->failurePointId}/recommendations/";
    }

    /**
     * @return array<AssetTypeRecommendationListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => AssetTypeRecommendationListItem::fromArray($item),
            $data
        );
    }
}
