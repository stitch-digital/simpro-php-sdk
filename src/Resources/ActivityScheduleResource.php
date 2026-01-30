<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\ActivitySchedules\ActivitySchedule;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\CreateActivityScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\DeleteActivityScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\GetActivityScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\ListActivitySchedulesRequest;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\UpdateActivityScheduleRequest;

/**
 * Resource for managing activity schedules.
 *
 * @property AbstractSimproConnector $connector
 */
final class ActivityScheduleResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all activity schedules.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListActivitySchedulesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific activity schedule.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $scheduleId, ?array $columns = null): ActivitySchedule
    {
        $request = new GetActivityScheduleRequest($this->companyId, $scheduleId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new activity schedule.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created schedule
     */
    public function create(array $data): int
    {
        $request = new CreateActivityScheduleRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing activity schedule.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $scheduleId, array $data): Response
    {
        $request = new UpdateActivityScheduleRequest($this->companyId, $scheduleId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an activity schedule.
     */
    public function delete(int|string $scheduleId): Response
    {
        $request = new DeleteActivityScheduleRequest($this->companyId, $scheduleId);

        return $this->connector->send($request);
    }
}
