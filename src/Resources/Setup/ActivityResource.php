<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\Activity;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\CreateActivityRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\DeleteActivityRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\GetActivityRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\ListActivitiesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\UpdateActivityRequest;

/**
 * Resource for managing Activitys.
 *
 * @property AbstractSimproConnector $connector
 */
final class ActivityResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
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
        $request = new ListActivitiesRequest($this->companyId);

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
    public function get(int|string $activityId, ?array $columns = null): Activity
    {
        $request = new GetActivityRequest($this->companyId, $activityId);

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
        $request = new CreateActivityRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $activityId, array $data): Response
    {
        $request = new UpdateActivityRequest($this->companyId, $activityId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $activityId): Response
    {
        $request = new DeleteActivityRequest($this->companyId, $activityId);

        return $this->connector->send($request);
    }
}
