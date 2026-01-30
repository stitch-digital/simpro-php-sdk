<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ContractInflation;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\Inflation\CreateContractInflationRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\Inflation\DeleteContractInflationRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\Inflation\GetContractInflationRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\Inflation\ListContractInflationRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\Inflation\UpdateContractInflationRequest;

/**
 * Resource for managing contract inflation records.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractInflationResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all inflation records for this contract.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractInflationRequest($this->companyId, $this->customerId, $this->contractId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific inflation record.
     */
    public function get(int|string $inflationId): ContractInflation
    {
        $request = new GetContractInflationRequest($this->companyId, $this->customerId, $this->contractId, $inflationId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new inflation record.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created inflation record
     */
    public function create(array $data): int
    {
        $request = new CreateContractInflationRequest($this->companyId, $this->customerId, $this->contractId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing inflation record.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $inflationId, array $data): Response
    {
        $request = new UpdateContractInflationRequest($this->companyId, $this->customerId, $this->contractId, $inflationId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an inflation record.
     */
    public function delete(int|string $inflationId): Response
    {
        $request = new DeleteContractInflationRequest($this->companyId, $this->customerId, $this->contractId, $inflationId);

        return $this->connector->send($request);
    }
}
