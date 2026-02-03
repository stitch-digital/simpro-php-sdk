<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\SetupCostCenter;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
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
}
