<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\CostCenter;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\CreateCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\DeleteCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\GetCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ListCostCentersRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\UpdateCostCenterRequest;

/**
 * Resource for managing cost centers within a job section.
 *
 * @property AbstractSimproConnector $connector
 */
final class CostCenterResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all cost centers for this section.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCostCentersRequest($this->companyId, $this->jobId, $this->sectionId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific cost center.
     */
    public function get(int|string $costCenterId): CostCenter
    {
        $request = new GetCostCenterRequest($this->companyId, $this->jobId, $this->sectionId, $costCenterId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new cost center.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created cost center
     */
    public function create(array $data): int
    {
        $request = new CreateCostCenterRequest($this->companyId, $this->jobId, $this->sectionId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing cost center.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $costCenterId, array $data): Response
    {
        $request = new UpdateCostCenterRequest($this->companyId, $this->jobId, $this->sectionId, $costCenterId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a cost center.
     */
    public function delete(int|string $costCenterId): Response
    {
        $request = new DeleteCostCenterRequest($this->companyId, $this->jobId, $this->sectionId, $costCenterId);

        return $this->connector->send($request);
    }
}
