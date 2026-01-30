<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\ServiceLevels\ListContractServiceLevelsRequest;

/**
 * Resource for managing contract service levels.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractServiceLevelResource extends BaseResource
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
     * List all service levels for this contract.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractServiceLevelsRequest($this->companyId, $this->customerId, $this->contractId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }
}
