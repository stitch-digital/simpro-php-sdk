<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetServiceLevel;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Assets\ServiceLevels\CreateAssetServiceLevelRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Assets\ServiceLevels\DeleteAssetServiceLevelRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Assets\ServiceLevels\GetAssetServiceLevelRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Assets\ServiceLevels\ListAssetServiceLevelsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Assets\ServiceLevels\ListDetailedAssetServiceLevelsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Assets\ServiceLevels\UpdateAssetServiceLevelRequest;

/**
 * Resource for managing AssetServiceLevels.
 *
 * @property AbstractSimproConnector $connector
 */
final class AssetServiceLevelResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all asset service levels with minimal fields (ID, Name).
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListAssetServiceLevelsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all asset service levels with full details.
     *
     * Returns AssetServiceLevel DTOs with all fields (ID, Name, Years, Months, Days, Archived).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedAssetServiceLevelsRequest($this->companyId);

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
    public function get(int|string $serviceLevelId, ?array $columns = null): AssetServiceLevel
    {
        $request = new GetAssetServiceLevelRequest($this->companyId, $serviceLevelId);

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
        $request = new CreateAssetServiceLevelRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $serviceLevelId, array $data): Response
    {
        $request = new UpdateAssetServiceLevelRequest($this->companyId, $serviceLevelId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $serviceLevelId): Response
    {
        $request = new DeleteAssetServiceLevelRequest($this->companyId, $serviceLevelId);

        return $this->connector->send($request);
    }
}
