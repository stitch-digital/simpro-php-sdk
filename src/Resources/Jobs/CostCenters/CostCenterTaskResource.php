<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Tasks\CostCenterTask;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Tasks\GetCostCenterTaskRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Tasks\ListCostCenterTasksRequest;

/**
 * Resource for accessing tasks within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class CostCenterTaskResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all tasks for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCostCenterTasksRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific task.
     */
    public function get(int|string $taskId): CostCenterTask
    {
        $request = new GetCostCenterTaskRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $taskId);

        return $this->connector->send($request)->dto();
    }
}
