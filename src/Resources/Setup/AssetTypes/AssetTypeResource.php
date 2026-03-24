<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetType;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\CreateAssetTypeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\DeleteAssetTypeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\GetAssetTypeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\ListAssetTypesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\ListDetailedAssetTypesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\UpdateAssetTypeRequest;

/**
 * Resource for managing asset types.
 *
 * @property AbstractSimproConnector $connector
 */
final class AssetTypeResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all asset types.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListAssetTypesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all asset types with full details.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedAssetTypesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific asset type.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $assetTypeId, ?array $columns = null): AssetType
    {
        $request = new GetAssetTypeRequest($this->companyId, $assetTypeId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new asset type.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateAssetTypeRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an asset type.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $assetTypeId, array $data): Response
    {
        $request = new UpdateAssetTypeRequest($this->companyId, $assetTypeId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an asset type.
     */
    public function delete(int|string $assetTypeId): Response
    {
        $request = new DeleteAssetTypeRequest($this->companyId, $assetTypeId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple asset types in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/assetTypes",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple asset types in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/assetTypes",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple asset types in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/assetTypes",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
