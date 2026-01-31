<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ContractLaborRate;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\LaborRates\CreateContractLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\LaborRates\DeleteContractLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\LaborRates\GetContractLaborRateRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\LaborRates\ListContractLaborRatesRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\LaborRates\UpdateContractLaborRateRequest;

/**
 * Resource for managing contract labor rates.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractLaborRateResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all labor rates for this contract.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractLaborRatesRequest($this->companyId, $this->customerId, $this->contractId);

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
     */
    public function get(int|string $laborRateId): ContractLaborRate
    {
        $request = new GetContractLaborRateRequest($this->companyId, $this->customerId, $this->contractId, $laborRateId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new labor rate.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created labor rate
     */
    public function create(array $data): int
    {
        $request = new CreateContractLaborRateRequest($this->companyId, $this->customerId, $this->contractId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing labor rate.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $laborRateId, array $data): Response
    {
        $request = new UpdateContractLaborRateRequest($this->companyId, $this->customerId, $this->contractId, $laborRateId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a labor rate.
     */
    public function delete(int|string $laborRateId): Response
    {
        $request = new DeleteContractLaborRateRequest($this->companyId, $this->customerId, $this->contractId, $laborRateId);

        return $this->connector->send($request);
    }
}
