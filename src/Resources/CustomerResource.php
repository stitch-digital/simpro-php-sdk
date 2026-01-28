<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Customers\Customer;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\CreateCompanyCustomerRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\DeleteCompanyCustomerRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\GetCompanyCustomerRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\ListCompanyCustomersRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\ListCustomersRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\UpdateCompanyCustomerRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class CustomerResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all customers (both companies and individuals).
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCustomersRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all company customers.
     *
     * Returns a QueryBuilder that supports fluent search, ordering, and filtering.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listCompanies(array $filters = []): QueryBuilder
    {
        $request = new ListCompanyCustomersRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get detailed information for a specific company customer.
     *
     * @param  array<string>|null  $columns
     */
    public function getCompany(int|string $customerId, ?array $columns = null): Customer
    {
        $request = new GetCompanyCustomerRequest($this->companyId, $customerId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new company customer.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created customer
     */
    public function createCompany(array $data): int
    {
        $request = new CreateCompanyCustomerRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing company customer.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateCompany(int|string $customerId, array $data): Response
    {
        $request = new UpdateCompanyCustomerRequest($this->companyId, $customerId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a company customer.
     */
    public function deleteCompany(int|string $customerId): Response
    {
        $request = new DeleteCompanyCustomerRequest($this->companyId, $customerId);

        return $this->connector->send($request);
    }
}
