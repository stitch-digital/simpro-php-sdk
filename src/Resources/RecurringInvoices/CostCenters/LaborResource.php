<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Labor\LaborItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\CreateLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\DeleteLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\GetLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\ListLaborRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\UpdateLaborRequest;

/**
 * Resource for managing labor items within a recurring invoice cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class LaborResource extends BaseResource
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
     * List all labor items for this cost center.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListLaborRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific labor item.
     */
    public function get(int|string $laborId): LaborItem
    {
        $request = new GetLaborRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $laborId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new labor item.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created labor item
     */
    public function create(array $data): int
    {
        $request = new CreateLaborRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing labor item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $laborId, array $data): Response
    {
        $request = new UpdateLaborRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $laborId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a labor item.
     */
    public function delete(int|string $laborId): Response
    {
        $request = new DeleteLaborRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $laborId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple labor items in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/labor",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple labor items in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/labor",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple labor items in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/labor",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
