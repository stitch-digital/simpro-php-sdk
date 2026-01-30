<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerIndividual;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\Individuals\CreateIndividualCustomerRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Individuals\DeleteIndividualCustomerRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Individuals\GetIndividualCustomerRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Individuals\ListIndividualCustomersDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Individuals\ListIndividualCustomersRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Individuals\UpdateIndividualCustomerRequest;

/**
 * Resource for managing individual customers.
 *
 * @property AbstractSimproConnector $connector
 */
final class CustomerIndividualResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all individual customers.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListIndividualCustomersRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all individual customers with all available columns.
     *
     * Returns detailed CustomerIndividual DTOs with full nested data structures.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListIndividualCustomersDetailedRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific individual customer.
     *
     * @param  array<string>|null  $columns  Optional columns to retrieve
     */
    public function get(int|string $customerId, ?array $columns = null): CustomerIndividual
    {
        $request = new GetIndividualCustomerRequest($this->companyId, $customerId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new individual customer.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created customer
     */
    public function create(array $data): int
    {
        $request = new CreateIndividualCustomerRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing individual customer.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customerId, array $data): Response
    {
        $request = new UpdateIndividualCustomerRequest($this->companyId, $customerId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an individual customer.
     */
    public function delete(int|string $customerId): Response
    {
        $request = new DeleteIndividualCustomerRequest($this->companyId, $customerId);

        return $this->connector->send($request);
    }
}
