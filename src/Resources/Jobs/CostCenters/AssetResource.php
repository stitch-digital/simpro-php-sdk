<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Assets\Asset;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Assets\BulkReplaceAssetsRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Assets\CreateAssetRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Assets\DeleteAssetRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Assets\GetAssetRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Assets\ListAssetsRequest;

/**
 * Resource for managing assets within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class AssetResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all assets for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListAssetsRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific asset.
     */
    public function get(int|string $assetId): Asset
    {
        $request = new GetAssetRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $assetId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new asset.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created asset
     */
    public function create(array $data): int
    {
        $request = new CreateAssetRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Bulk replace all assets.
     *
     * @param  array<array<string, mixed>>  $assets
     */
    public function bulkReplace(array $assets): Response
    {
        $request = new BulkReplaceAssetsRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $assets);

        return $this->connector->send($request);
    }

    /**
     * Delete an asset.
     */
    public function delete(int|string $assetId): Response
    {
        $request = new DeleteAssetRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $assetId);

        return $this->connector->send($request);
    }
}
