<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\Labor;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\FitTime;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\FitTimes\CreateFitTimeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\FitTimes\DeleteFitTimeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\FitTimes\GetFitTimeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\FitTimes\ListDetailedFitTimesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\FitTimes\ListFitTimesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Labor\FitTimes\UpdateFitTimeRequest;

/**
 * Resource for managing FitTimes.
 *
 * @property AbstractSimproConnector $connector
 */
final class FitTimeResource extends BaseResource
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
        $request = new ListFitTimesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all fit times with full details.
     *
     * Returns FitTime DTOs with all fields (ID, Name, Multiplier, DisplayOrder, Archived).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedFitTimesRequest($this->companyId);

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
    public function get(int|string $fitTimeId, ?array $columns = null): FitTime
    {
        $request = new GetFitTimeRequest($this->companyId, $fitTimeId);

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
        $request = new CreateFitTimeRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $fitTimeId, array $data): Response
    {
        $request = new UpdateFitTimeRequest($this->companyId, $fitTimeId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $fitTimeId): Response
    {
        $request = new DeleteFitTimeRequest($this->companyId, $fitTimeId);

        return $this->connector->send($request);
    }
}
