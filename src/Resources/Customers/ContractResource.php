<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\Contract;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\CreateContractRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\DeleteContractRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\GetContractRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\ListContractsDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\ListContractsRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\UpdateContractRequest;

/**
 * Resource for managing customer contracts.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $customerId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all contracts for this customer.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractsRequest($this->companyId, $this->customerId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * List all contracts for this customer with all available columns.
     *
     * Returns detailed Contract DTOs with full nested data structures.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function listDetailed(array $filters = []): QueryBuilder
    {
        $request = new ListContractsDetailedRequest($this->companyId, $this->customerId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific contract.
     *
     * @param  array<string>|null  $columns  Optional columns to retrieve
     */
    public function get(int|string $contractId, ?array $columns = null): Contract
    {
        $request = new GetContractRequest($this->companyId, $this->customerId, $contractId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new contract.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created contract
     */
    public function create(array $data): int
    {
        $request = new CreateContractRequest($this->companyId, $this->customerId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing contract.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $contractId, array $data): Response
    {
        $request = new UpdateContractRequest($this->companyId, $this->customerId, $contractId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a contract.
     */
    public function delete(int|string $contractId): Response
    {
        $request = new DeleteContractRequest($this->companyId, $this->customerId, $contractId);

        return $this->connector->send($request);
    }
}
