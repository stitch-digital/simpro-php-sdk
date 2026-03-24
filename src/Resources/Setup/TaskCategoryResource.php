<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\TaskCategory;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tasks\Categories\CreateTaskCategoryRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tasks\Categories\DeleteTaskCategoryRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tasks\Categories\GetTaskCategoryRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tasks\Categories\ListDetailedTaskCategoriesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tasks\Categories\ListTaskCategoriesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tasks\Categories\UpdateTaskCategoryRequest;

/**
 * Resource for managing TaskCategorys.
 *
 * @property AbstractSimproConnector $connector
 */
final class TaskCategoryResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all task categories with minimal fields (ID, Name).
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListTaskCategoriesRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all task categories with full details.
     *
     * Returns TaskCategory DTOs with all fields (ID, Name, Archived).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedTaskCategoriesRequest($this->companyId);

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
    public function get(int|string $taskCategoryId, ?array $columns = null): TaskCategory
    {
        $request = new GetTaskCategoryRequest($this->companyId, $taskCategoryId);

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
        $request = new CreateTaskCategoryRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $taskCategoryId, array $data): Response
    {
        $request = new UpdateTaskCategoryRequest($this->companyId, $taskCategoryId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $taskCategoryId): Response
    {
        $request = new DeleteTaskCategoryRequest($this->companyId, $taskCategoryId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple task categories in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/tasks/categories",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple task categories in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/tasks/categories",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple task categories in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/tasks/categories",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
