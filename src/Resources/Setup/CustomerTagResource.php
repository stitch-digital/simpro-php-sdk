<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerTag;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\CreateCustomerTagRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\DeleteCustomerTagRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\GetCustomerTagRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Tags\Customers\ListCustomerTagsRequest;
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
}
