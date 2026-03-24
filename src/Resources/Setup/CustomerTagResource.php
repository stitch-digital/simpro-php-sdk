<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerTag;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\CreateCustomerTagRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\DeleteCustomerTagRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\GetCustomerTagRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\ListCustomerTagsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\ListDetailedCustomerTagsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\UpdateCustomerTagRequest;

/**
 * Resource for managing CustomerTags.
 *
 * @property AbstractSimproConnector $connector
 */
final class CustomerTagResource extends BaseResource
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
        $request = new ListCustomerTagsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all customer tags with full details.
     *
     * Returns CustomerTag DTOs with all fields (ID, Name, Archived).
     *
     * @param  array<string, mixed>  $filters
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListDetailedCustomerTagsRequest($this->companyId);

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
    public function get(int|string $tagId, ?array $columns = null): CustomerTag
    {
        $request = new GetCustomerTagRequest($this->companyId, $tagId);

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
        $request = new CreateCustomerTagRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $tagId, array $data): Response
    {
        $request = new UpdateCustomerTagRequest($this->companyId, $tagId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $tagId): Response
    {
        $request = new DeleteCustomerTagRequest($this->companyId, $tagId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple customer tags in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/tags/customers",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple customer tags in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/tags/customers",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple customer tags in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/setup/tags/customers",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
