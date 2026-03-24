<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Stock\StockItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Stock\CreateStockRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Stock\GetStockRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Stock\ListStockRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Stock\UpdateStockRequest;

/**
 * Resource for managing stock within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class StockResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all stock items for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListStockRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific stock item.
     */
    public function get(int|string $stockId): StockItem
    {
        $request = new GetStockRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $stockId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new stock item.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created stock item
     */
    public function create(array $data): int
    {
        $request = new CreateStockRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing stock item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $stockId, array $data): Response
    {
        $request = new UpdateStockRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $stockId, $data);

        return $this->connector->send($request);
    }

    /**
     * Create multiple stock items in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/stock",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple stock items in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/stock",
            $data,
        );

        return $this->connector->send($request)->dto();
    }
}
