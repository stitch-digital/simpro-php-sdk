<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeFolder;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Folders\CreateAssetTypeFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Folders\DeleteAssetTypeFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Folders\GetAssetTypeFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Folders\ListAssetTypeFoldersRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Folders\UpdateAssetTypeFolderRequest;

/**
 * Resource for managing AssetTypeFolders.
 *
 * @property AbstractSimproConnector $connector
 */
final class AssetTypeFolderResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $assetTypeId,
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
        $request = new ListAssetTypeFoldersRequest($this->companyId, $this->assetTypeId);

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
    public function get(int|string $folderId, ?array $columns = null): AssetTypeFolder
    {
        $request = new GetAssetTypeFolderRequest($this->companyId, $this->assetTypeId, $folderId);

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
        $request = new CreateAssetTypeFolderRequest($this->companyId, $this->assetTypeId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $folderId, array $data): Response
    {
        $request = new UpdateAssetTypeFolderRequest($this->companyId, $this->assetTypeId, $folderId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $folderId): Response
    {
        $request = new DeleteAssetTypeFolderRequest($this->companyId, $this->assetTypeId, $folderId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple asset type folders in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/attachments/folders",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple asset type folders in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/attachments/folders",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple asset type folders in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/{$this->assetTypeId}/attachments/folders",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
