<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerGroup;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups\CreateCustomerGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups\DeleteCustomerGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups\GetCustomerGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups\ListCustomerGroupsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups\UpdateCustomerGroupRequest;

/**
 * Resource for managing customer groups.
 *
 * @property AbstractSimproConnector $connector
 */
final class CustomerGroupResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all customer groups.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCustomerGroupsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific customer group.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $customerGroupId, ?array $columns = null): CustomerGroup
    {
        $request = new GetCustomerGroupRequest($this->companyId, $customerGroupId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new customer group.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreateCustomerGroupRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a customer group.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customerGroupId, array $data): Response
    {
        $request = new UpdateCustomerGroupRequest($this->companyId, $customerGroupId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a customer group.
     */
    public function delete(int|string $customerGroupId): Response
    {
        $request = new DeleteCustomerGroupRequest($this->companyId, $customerGroupId);

        return $this->connector->send($request);
    }
}
