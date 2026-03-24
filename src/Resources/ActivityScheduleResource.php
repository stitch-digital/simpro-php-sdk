<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\ActivitySchedules\ActivitySchedule;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\CreateActivityScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\DeleteActivityScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\GetActivityScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\ListActivitySchedulesRequest;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\ListDetailedActivitySchedulesRequest;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\UpdateActivityScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;

/**
 * Resource for managing activity schedules.
 *
 * @property AbstractSimproConnector $connector
 */
final class ActivityScheduleResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all activity schedules.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     * Returns ActivityScheduleListItem DTOs with summary fields.
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
     * List all activity schedules with full details.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     * Returns full ActivitySchedule DTOs with all fields including notes, blocks, etc.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedActivitySchedulesRequest($this->companyId);

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

    /**
     * Create multiple activity schedules in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/activitySchedules",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple activity schedules in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/activitySchedules",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple activity schedules in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/activitySchedules",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
