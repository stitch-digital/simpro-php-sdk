<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\ProjectTag;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Projects\CreateProjectTagRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Projects\DeleteProjectTagRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Projects\GetProjectTagRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Projects\ListProjectTagsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Projects\UpdateProjectTagRequest;

/**
 * Resource for managing ProjectTags.
 *
 * @property AbstractSimproConnector $connector
 */
final class ProjectTagResource extends BaseResource
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
        $request = new ListProjectTagsRequest($this->companyId);

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
    public function get(int|string $tagId, ?array $columns = null): ProjectTag
    {
        $request = new GetProjectTagRequest($this->companyId, $tagId);

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
        $request = new CreateProjectTagRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $tagId, array $data): Response
    {
        $request = new UpdateProjectTagRequest($this->companyId, $tagId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $tagId): Response
    {
        $request = new DeleteProjectTagRequest($this->companyId, $tagId);

        return $this->connector->send($request);
    }
}
