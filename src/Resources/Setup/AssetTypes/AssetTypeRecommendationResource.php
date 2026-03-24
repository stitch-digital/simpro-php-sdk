<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeRecommendation;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Recommendations\CreateAssetTypeRecommendationRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Recommendations\DeleteAssetTypeRecommendationRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Recommendations\GetAssetTypeRecommendationRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Recommendations\ListAssetTypeRecommendationsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Recommendations\UpdateAssetTypeRecommendationRequest;

/**
 * Resource for managing AssetTypeRecommendations.
 *
 * @property AbstractSimproConnector $connector
 */
final class AssetTypeRecommendationResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $serviceLevelId,
        private readonly int|string $failurePointId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListAssetTypeRecommendationsRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId, $this->failurePointId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific item.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $recommendationId, ?array $columns = null): AssetTypeRecommendation
    {
        $request = new GetAssetTypeRecommendationRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId, $this->failurePointId, $recommendationId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new item.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateAssetTypeRecommendationRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId, $this->failurePointId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $recommendationId, array $data): Response
    {
        $request = new UpdateAssetTypeRecommendationRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId, $this->failurePointId, $recommendationId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $recommendationId): Response
    {
        $request = new DeleteAssetTypeRecommendationRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId, $this->failurePointId, $recommendationId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple asset type recommendations in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/serviceLevels/{$this->serviceLevelId}/failurePoints/{$this->failurePointId}/recommendations",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple asset type recommendations in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/serviceLevels/{$this->serviceLevelId}/failurePoints/{$this->failurePointId}/recommendations",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple asset type recommendations in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/serviceLevels/{$this->serviceLevelId}/failurePoints/{$this->failurePointId}/recommendations",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
