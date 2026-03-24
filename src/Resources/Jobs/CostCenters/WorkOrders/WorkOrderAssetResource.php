<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrders;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\WorkOrderAsset;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\DeleteWorkOrderAssetRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\GetWorkOrderAssetRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\ListWorkOrderAssetsDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\ListWorkOrderAssetsRequest;

/**
 * Resource for managing work order assets.
 *
 * @property AbstractSimproConnector $connector
 */
final class WorkOrderAssetResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int $jobId,
        private readonly int $sectionId,
        private readonly int $costCenterId,
        private readonly int $workOrderId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all assets for this work order.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListWorkOrderAssetsRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId
        );

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all assets with detailed information.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListWorkOrderAssetsDetailedRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId
        );

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific work order asset.
     */
    public function get(int $assetId): WorkOrderAsset
    {
        $request = new GetWorkOrderAssetRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $assetId
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete a work order asset.
     */
    public function delete(int $assetId): Response
    {
        $request = new DeleteWorkOrderAssetRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $assetId
        );

        return $this->connector->send($request);
    }

    /**
     * Delete multiple work order assets in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/workOrders/{$this->workOrderId}/assets",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
