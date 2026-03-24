<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Catalogs\CatalogItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Catalogs\CreateQuoteCostCenterCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Catalogs\DeleteQuoteCostCenterCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Catalogs\GetQuoteCostCenterCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Catalogs\ListQuoteCostCenterCatalogsRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Catalogs\UpdateQuoteCostCenterCatalogRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class QuoteCostCenterCatalogResource extends BaseResource
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
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteCostCenterCatalogsRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    public function get(int|string $catalogId): CatalogItem
    {
        $request = new GetQuoteCostCenterCatalogRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $catalogId);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateQuoteCostCenterCatalogRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $catalogId, array $data): Response
    {
        $request = new UpdateQuoteCostCenterCatalogRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $catalogId, $data);

        return $this->connector->send($request);
    }

    public function delete(int|string $catalogId): Response
    {
        $request = new DeleteQuoteCostCenterCatalogRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $catalogId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple quote cost center catalogs in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/catalogs",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple quote cost center catalogs in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/catalogs",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple quote cost center catalogs in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/catalogs",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
