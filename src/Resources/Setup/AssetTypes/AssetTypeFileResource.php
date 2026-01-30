<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeFile;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Files\CreateAssetTypeFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Files\DeleteAssetTypeFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Files\GetAssetTypeFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Files\ListAssetTypeFilesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\Files\UpdateAssetTypeFileRequest;

/**
 * Resource for managing AssetTypeFiles.
 *
 * @property AbstractSimproConnector $connector
 */
final class AssetTypeFileResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
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
        $request = new ListAssetTypeFilesRequest($this->companyId, $this->assetTypeId);

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
    public function get(int|string $fileId, ?array $columns = null): AssetTypeFile
    {
        $request = new GetAssetTypeFileRequest($this->companyId, $this->assetTypeId, $fileId);

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
        $request = new CreateAssetTypeFileRequest($this->companyId, $this->assetTypeId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $fileId, array $data): Response
    {
        $request = new UpdateAssetTypeFileRequest($this->companyId, $this->assetTypeId, $fileId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $fileId): Response
    {
        $request = new DeleteAssetTypeFileRequest($this->companyId, $this->assetTypeId, $fileId);

        return $this->connector->send($request);
    }
}
