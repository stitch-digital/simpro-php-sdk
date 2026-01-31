<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeTestReading;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\TestReadings\CreateAssetTypeTestReadingRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\TestReadings\DeleteAssetTypeTestReadingRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\TestReadings\GetAssetTypeTestReadingRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\TestReadings\ListAssetTypeTestReadingsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\TestReadings\UpdateAssetTypeTestReadingRequest;

/**
 * Resource for managing AssetTypeTestReadings.
 *
 * @property AbstractSimproConnector $connector
 */
final class AssetTypeTestReadingResource extends BaseResource
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
        $request = new ListAssetTypeTestReadingsRequest($this->companyId, $this->assetTypeId);

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
    public function get(int|string $testReadingId, ?array $columns = null): AssetTypeTestReading
    {
        $request = new GetAssetTypeTestReadingRequest($this->companyId, $this->assetTypeId, $testReadingId);

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
        $request = new CreateAssetTypeTestReadingRequest($this->companyId, $this->assetTypeId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $testReadingId, array $data): Response
    {
        $request = new UpdateAssetTypeTestReadingRequest($this->companyId, $this->assetTypeId, $testReadingId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $testReadingId): Response
    {
        $request = new DeleteAssetTypeTestReadingRequest($this->companyId, $this->assetTypeId, $testReadingId);

        return $this->connector->send($request);
    }
}
