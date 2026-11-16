<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Prebuilds\PrebuildItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\CreatePrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\DeletePrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\GetPrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\ListPrebuildsRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\UpdatePrebuildRequest;

/**
 * Resource for managing prebuilds within a recurring invoice cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class PrebuildResource extends BaseResource
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
     * List all prebuilds for this cost center.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListPrebuildsRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific prebuild.
     */
    public function get(int|string $prebuildId): PrebuildItem
    {
        $request = new GetPrebuildRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $prebuildId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new prebuild.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created prebuild
     */
    public function create(array $data): int
    {
        $request = new CreatePrebuildRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing prebuild.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $prebuildId, array $data): Response
    {
        $request = new UpdatePrebuildRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $prebuildId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a prebuild.
     */
    public function delete(int|string $prebuildId): Response
    {
        $request = new DeletePrebuildRequest($this->companyId, $this->recurringInvoiceId, $this->sectionId, $this->costCenterId, $prebuildId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple prebuilds in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/prebuilds",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple prebuilds in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/prebuilds",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple prebuilds in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/prebuilds",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
