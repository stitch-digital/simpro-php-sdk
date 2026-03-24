<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\SetupCostCenter;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\CreateSetupCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\DeleteSetupCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\GetSetupCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\ListDetailedSetupCostCentersRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\ListSetupCostCentersRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\UpdateSetupCostCenterRequest;

/**
 * Resource for managing setup cost centers.
 *
 * @property AbstractSimproConnector $connector
 */
final class CostCenterResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all cost centers.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListSetupCostCentersRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all cost centers with full details.
     *
     * Returns SetupCostCenter DTOs with all fields including Rates.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedSetupCostCentersRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific cost center.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $costCenterId, ?array $columns = null): SetupCostCenter
    {
        $request = new GetSetupCostCenterRequest($this->companyId, $costCenterId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new cost center.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateSetupCostCenterRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a cost center.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $costCenterId, array $data): Response
    {
        $request = new UpdateSetupCostCenterRequest($this->companyId, $costCenterId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a cost center.
     */
    public function delete(int|string $costCenterId): Response
    {
        $request = new DeleteSetupCostCenterRequest($this->companyId, $costCenterId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple setup cost centers in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/accounts/costCenters",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple setup cost centers in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/accounts/costCenters",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple setup cost centers in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/accounts/costCenters",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
