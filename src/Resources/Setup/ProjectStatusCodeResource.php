<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\ProjectStatusCode;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects\CreateProjectStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects\DeleteProjectStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects\GetProjectStatusCodeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects\ListDetailedProjectStatusCodesRequest;
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
     * List all project status codes with full details.
     *
     * Returns ProjectStatusCode DTOs with all fields (ID, Name, Color, Priority).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedProjectStatusCodesRequest($this->companyId);

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

    /**
     * Create multiple project status codes in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/projects",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple project status codes in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/projects",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple project status codes in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/projects",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
