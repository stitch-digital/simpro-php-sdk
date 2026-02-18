<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Schedules\CostCenterSchedule;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Schedules\CreateQuoteCostCenterScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Schedules\DeleteQuoteCostCenterScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Schedules\GetQuoteCostCenterScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Schedules\ListQuoteCostCenterSchedulesRequest;
use Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\Schedules\UpdateQuoteCostCenterScheduleRequest;

/**
 * Resource for managing schedules within a quote cost center.
 *
 * @property AbstractSimproConnector $connector
 */
final class QuoteCostCenterScheduleResource extends BaseResource
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
     * List all schedules for this quote cost center.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListQuoteCostCenterSchedulesRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);

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
        $request = new GetQuoteCostCenterScheduleRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $scheduleId);

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
        $request = new CreateQuoteCostCenterScheduleRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing schedule.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $scheduleId, array $data): Response
    {
        $request = new UpdateQuoteCostCenterScheduleRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $scheduleId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a schedule.
     */
    public function delete(int|string $scheduleId): Response
    {
        $request = new DeleteQuoteCostCenterScheduleRequest($this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId, $scheduleId);

        return $this->connector->send($request);
    }
}
