<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ServiceFees\ServiceFeeItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\CreateServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\DeleteServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\GetServiceFeeRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\ListServiceFeesRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees\UpdateServiceFeeRequest;

/**
 * Resource for managing service fees within a recurring invoice cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class ServiceFeeResource extends BaseResource
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
     * List all service fees for this cost center.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListServiceFeesRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific service fee.
     */
    public function get(int|string $serviceFeeId): ServiceFeeItem
    {
        $request = new GetServiceFeeRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $serviceFeeId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new service fee.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created service fee
     */
    public function create(array $data): int
    {
        $request = new CreateServiceFeeRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing service fee.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $serviceFeeId, array $data): Response
    {
        $request = new UpdateServiceFeeRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $serviceFeeId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a service fee.
     */
    public function delete(int|string $serviceFeeId): Response
    {
        $request = new DeleteServiceFeeRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $serviceFeeId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple service fees in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/serviceFees",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple service fees in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/serviceFees",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple service fees in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/serviceFees",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
