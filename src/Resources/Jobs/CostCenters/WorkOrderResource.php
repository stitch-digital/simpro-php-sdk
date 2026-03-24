<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\WorkOrderListItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\CreateWorkOrderRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\GetWorkOrderRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\ListWorkOrdersDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\ListWorkOrdersRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\UpdateWorkOrderRequest;

/**
 * Resource for managing work orders within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class WorkOrderResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int $jobId,
        private readonly int $sectionId,
        private readonly int $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all work orders for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListWorkOrdersRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all work orders with detailed information.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = [], bool $includeMaterials = true): QueryBuilder
    {
        $request = new ListWorkOrdersDetailedRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $includeMaterials);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific work order.
     */
    public function get(int $workOrderId): WorkOrderListItem
    {
        $request = new GetWorkOrderRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $workOrderId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new work order.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created work order
     */
    public function create(array $data): int
    {
        $request = new CreateWorkOrderRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing work order.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $workOrderId, array $data): Response
    {
        $request = new UpdateWorkOrderRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $workOrderId, $data);

        return $this->connector->send($request);
    }
}
