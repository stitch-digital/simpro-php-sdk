<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Customers;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\CustomFields\GetContractCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\CustomFields\ListContractCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\CustomFields\UpdateContractCustomFieldRequest;

/**
 * Resource for managing contract custom fields.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractCustomFieldResource extends BaseResource
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
     * List all custom fields for this contract.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractCustomFieldsRequest($this->companyId, $this->customerId, $this->contractId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific custom field.
     */
    public function get(int|string $customFieldId): CustomField
    {
        $request = new GetContractCustomFieldRequest($this->companyId, $this->customerId, $this->contractId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateContractCustomFieldRequest($this->companyId, $this->customerId, $this->contractId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
