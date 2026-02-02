<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Customers\LaborRates\CustomerLaborRate;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\LaborRates\CreateCustomerLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\LaborRates\DeleteCustomerLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\LaborRates\GetCustomerLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\LaborRates\ListCustomerLaborRatesRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\LaborRates\UpdateCustomerLaborRateRequest;

/**
 * Resource for managing customer labor rates.
 *
 * @property AbstractSimproConnector $connector
 */
final class CustomerLaborRateResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $customerId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all labor rates for this customer.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCustomerLaborRatesRequest($this->companyId, $this->customerId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific labor rate.
     *
     * @param  array<string>|null  $columns  Optional columns to retrieve
     */
    public function get(int|string $laborRateId, ?array $columns = null): CustomerLaborRate
    {
        $request = new GetCustomerLaborRateRequest($this->companyId, $this->customerId, $laborRateId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new labor rate.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): CustomerLaborRate
    {
        $request = new CreateCustomerLaborRateRequest($this->companyId, $this->customerId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing labor rate.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $laborRateId, array $data): Response
    {
        $request = new UpdateCustomerLaborRateRequest($this->companyId, $this->customerId, $laborRateId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a labor rate.
     */
    public function delete(int|string $laborRateId): Response
    {
        $request = new DeleteCustomerLaborRateRequest($this->companyId, $this->customerId, $laborRateId);

        return $this->connector->send($request);
    }
}
