<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\WorkOrder;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\WorkOrders\CreateQuoteCostCenterWorkOrderRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\WorkOrders\GetQuoteCostCenterWorkOrderRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\WorkOrders\ListQuoteCostCenterWorkOrdersRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\WorkOrders\UpdateQuoteCostCenterWorkOrderRequest;

/**
 * Resource for managing work orders within a quote cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class QuoteCostCenterWorkOrderResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all work orders for this quote cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteCostCenterWorkOrdersRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);

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
    public function get(int|string $workOrderId): WorkOrder
    {
        $request = new GetQuoteCostCenterWorkOrderRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $workOrderId);

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
        $request = new CreateQuoteCostCenterWorkOrderRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing work order.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $workOrderId, array $data): Response
    {
        $request = new UpdateQuoteCostCenterWorkOrderRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $workOrderId, $data);

        return $this->connector->send($request);
    }
}
