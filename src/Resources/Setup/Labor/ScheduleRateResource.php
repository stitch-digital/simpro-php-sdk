<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\Labor;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\ScheduleRate;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ScheduleRates\CreateScheduleRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ScheduleRates\DeleteScheduleRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ScheduleRates\GetScheduleRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ScheduleRates\ListScheduleRatesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ScheduleRates\UpdateScheduleRateRequest;

/**
 * Resource for managing ScheduleRates.
 *
 * @property AbstractSimproConnector $connector
 */
final class ScheduleRateResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListScheduleRatesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific item.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $scheduleRateId, ?array $columns = null): ScheduleRate
    {
        $request = new GetScheduleRateRequest($this->companyId, $scheduleRateId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new item.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateScheduleRateRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $scheduleRateId, array $data): Response
    {
        $request = new UpdateScheduleRateRequest($this->companyId, $scheduleRateId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $scheduleRateId): Response
    {
        $request = new DeleteScheduleRateRequest($this->companyId, $scheduleRateId);

        return $this->connector->send($request);
    }
}
