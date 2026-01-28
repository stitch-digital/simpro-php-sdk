<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Schedules\Schedule;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Schedules\GetScheduleRequest;
use Simpro\PhpSdk\Simpro\Requests\Schedules\ListSchedulesRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class ScheduleResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all schedules.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListSchedulesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific schedule.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $scheduleId, ?array $columns = null): Schedule
    {
        $request = new GetScheduleRequest($this->companyId, $scheduleId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }
}
