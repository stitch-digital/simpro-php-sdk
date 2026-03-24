<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\CostCenter;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\CreateQuoteCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\DeleteQuoteCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\GetQuoteCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ListQuoteCostCentersRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\UpdateQuoteCostCenterRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteCostCenterResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $sectionId,
    ) {
        parent::__construct($connector);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteCostCentersRequest($this->companyId, $this->quoteId, $this->sectionId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $costCenterId): CostCenter
    {
        $request = new GetQuoteCostCenterRequest($this->companyId, $this->quoteId, $this->sectionId, $costCenterId);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteCostCenterRequest($this->companyId, $this->quoteId, $this->sectionId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $costCenterId, array $data): Response
    {
        $request = new UpdateQuoteCostCenterRequest($this->companyId, $this->quoteId, $this->sectionId, $costCenterId, $data);

        return $this->connector->send($request);
    }

    public function delete(int|string $costCenterId): Response
    {
        $request = new DeleteQuoteCostCenterRequest($this->companyId, $this->quoteId, $this->sectionId, $costCenterId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple quote cost centers in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/sections/{$this->sectionId}/costCenters",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple quote cost centers in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/sections/{$this->sectionId}/costCenters",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple quote cost centers in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/sections/{$this->sectionId}/costCenters",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
