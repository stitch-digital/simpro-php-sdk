<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeServiceLevel;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\ServiceLevels\CreateAssetTypeServiceLevelRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\ServiceLevels\DeleteAssetTypeServiceLevelRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\ServiceLevels\GetAssetTypeServiceLevelRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\ServiceLevels\ListAssetTypeServiceLevelsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\ServiceLevels\UpdateAssetTypeServiceLevelRequest;

/**
 * Resource for managing AssetTypeServiceLevels.
 *
 * @property AbstractSimproConnector $connector
 */
final class AssetTypeServiceLevelResource extends BaseResource
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
        $request = new ListAssetTypeServiceLevelsRequest($this->companyId, $this->assetTypeId);

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
    public function get(int|string $serviceLevelId, ?array $columns = null): AssetTypeServiceLevel
    {
        $request = new GetAssetTypeServiceLevelRequest($this->companyId, $this->assetTypeId, $serviceLevelId);

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
        $request = new CreateAssetTypeServiceLevelRequest($this->companyId, $this->assetTypeId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $serviceLevelId, array $data): Response
    {
        $request = new UpdateAssetTypeServiceLevelRequest($this->companyId, $this->assetTypeId, $serviceLevelId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $serviceLevelId): Response
    {
        $request = new DeleteAssetTypeServiceLevelRequest($this->companyId, $this->assetTypeId, $serviceLevelId);

        return $this->connector->send($request);
    }
}
