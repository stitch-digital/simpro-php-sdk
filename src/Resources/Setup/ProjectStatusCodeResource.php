<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\ProjectStatusCode;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects\CreateProjectStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects\DeleteProjectStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects\GetProjectStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects\ListProjectStatusCodesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects\UpdateProjectStatusCodeRequest;

/**
 * Resource for managing ProjectStatusCodes.
 *
 * @property AbstractSimproConnector $connector
 */
final class ProjectStatusCodeResource extends BaseResource
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
        $request = new ListProjectStatusCodesRequest($this->companyId);

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
    public function get(int|string $statusCodeId, ?array $columns = null): ProjectStatusCode
    {
        $request = new GetProjectStatusCodeRequest($this->companyId, $statusCodeId);

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
        $request = new CreateProjectStatusCodeRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $statusCodeId, array $data): Response
    {
        $request = new UpdateProjectStatusCodeRequest($this->companyId, $statusCodeId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $statusCodeId): Response
    {
        $request = new DeleteProjectStatusCodeRequest($this->companyId, $statusCodeId);

        return $this->connector->send($request);
    }
}
