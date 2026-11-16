<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Catalogs\CatalogItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\CreateCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\DeleteCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\GetCatalogRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\ListCatalogsRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Catalogs\UpdateCatalogRequest;

/**
 * Resource for managing catalog items within a recurring invoice cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class CatalogResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $recurringInvoiceId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all catalog items for this cost center.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCatalogsRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific catalog item.
     */
    public function get(int|string $catalogId): CatalogItem
    {
        $request = new GetCatalogRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $catalogId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new catalog item.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created catalog item
     */
    public function create(array $data): int
    {
        $request = new CreateCatalogRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing catalog item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $catalogId, array $data): Response
    {
        $request = new UpdateCatalogRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $catalogId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a catalog item.
     */
    public function delete(int|string $catalogId): Response
    {
        $request = new DeleteCatalogRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $catalogId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple catalog items in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/catalogs",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple catalog items in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/catalogs",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple catalog items in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/catalogs",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
