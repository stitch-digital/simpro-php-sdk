<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Schedules\CostCenterSchedule;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Schedules\CreateCostCenterScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Schedules\DeleteCostCenterScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Schedules\GetCostCenterScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Schedules\ListCostCenterSchedulesRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\Schedules\UpdateCostCenterScheduleRequest;

/**
 * Resource for managing schedules within a cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class CostCenterScheduleResource extends BaseResource
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
     * List all schedules for this cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCostCenterSchedulesRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific schedule.
     */
    public function get(int|string $scheduleId): CostCenterSchedule
    {
        $request = new GetCostCenterScheduleRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $scheduleId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new schedule.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created schedule
     */
    public function create(array $data): int
    {
        $request = new CreateCostCenterScheduleRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing schedule.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $scheduleId, array $data): Response
    {
        $request = new UpdateCostCenterScheduleRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $scheduleId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a schedule.
     */
    public function delete(int|string $scheduleId): Response
    {
        $request = new DeleteCostCenterScheduleRequest($this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $scheduleId);

        return $this->connector->send($request);
    }
}
