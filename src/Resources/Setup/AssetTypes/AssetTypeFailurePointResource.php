<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeFailurePoint;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\FailurePoints\CreateAssetTypeFailurePointRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\FailurePoints\DeleteAssetTypeFailurePointRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\FailurePoints\GetAssetTypeFailurePointRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\FailurePoints\ListAssetTypeFailurePointsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\FailurePoints\UpdateAssetTypeFailurePointRequest;

/**
 * Resource for managing AssetTypeFailurePoints.
 *
 * @property AbstractSimproConnector $connector
 */
final class AssetTypeFailurePointResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $serviceLevelId,
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
        $request = new ListAssetTypeFailurePointsRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId);

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
    public function get(int|string $failurePointId, ?array $columns = null): AssetTypeFailurePoint
    {
        $request = new GetAssetTypeFailurePointRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId, $failurePointId);

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
        $request = new CreateAssetTypeFailurePointRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $failurePointId, array $data): Response
    {
        $request = new UpdateAssetTypeFailurePointRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId, $failurePointId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $failurePointId): Response
    {
        $request = new DeleteAssetTypeFailurePointRequest($this->companyId, $this->assetTypeId, $this->serviceLevelId, $failurePointId);

        return $this->connector->send($request);
    }
}
