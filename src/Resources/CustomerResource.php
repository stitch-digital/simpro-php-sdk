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
use Simpro\PhpSdk\Simpro\Requests\Customers\ListCompanyCustomersDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\ListCompanyCustomersRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\ListCustomersRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\UpdateCompanyCustomerRequest;
use Simpro\PhpSdk\Simpro\Resources\Customers\CustomerIndividualResource;
use Simpro\PhpSdk\Simpro\Scopes\Customers\CustomerScope;

/**
 * @property AbstractSimproConnector $connector
 */
final class CustomerResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
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
     * List all company customers with detailed information.
     *
     * Returns CustomerCompanyListDetailedItem DTOs with all available columns including:
     * Address, BillingAddress, CustomerType, Tags, AmountOwing, Profile, Banking,
     * Sites, Contracts, Contacts, ResponseTimes, CustomFields, and timestamps.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     *
     * @example
     * // Get all company customers with full details
     * $customers = $connector->customers(companyId: 0)->listCompaniesDetailed()->all();
     *
     * // With fluent search
     * $result = $connector->customers(companyId: 0)->listCompaniesDetailed()
     *     ->search(Search::make()->column('CompanyName')->find('Acme'))
     *     ->orderByDesc('DateModified')
     *     ->collect();
     */
    public function listCompaniesDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListCompanyCustomersDetailedRequest($this->companyId);

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

    /**
     * Access individual customer operations.
     *
     * @example
     * // List all individual customers
     * $connector->customers(companyId: 0)->individuals()->list()->all();
     *
     * // Get a specific individual
     * $connector->customers(companyId: 0)->individuals()->get(customerId: 123);
     */
    public function individuals(): CustomerIndividualResource
    {
        return new CustomerIndividualResource($this->connector, $this->companyId);
    }

    /**
     * Navigate to a specific customer scope for accessing nested resources.
     *
     * @example
     * // Access customer contacts
     * $connector->customers(companyId: 0)->customer(customerId: 456)->contacts()->list();
     *
     * // Access customer contracts
     * $connector->customers(companyId: 0)->customer(customerId: 456)->contracts()->list();
     */
    public function customer(int|string $customerId): CustomerScope
    {
        return new CustomerScope($this->connector, $this->companyId, $customerId);
    }
}
