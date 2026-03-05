<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Contractors;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Contractors\CustomFields\GetContractorCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\CustomFields\ListContractorCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\CustomFields\UpdateContractorCustomFieldRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class ContractorCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $contractorId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this contractor.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorCustomFieldsRequest($this->companyId, $this->contractorId);

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
        $request = new GetContractorCustomFieldRequest($this->companyId, $this->contractorId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateContractorCustomFieldRequest($this->companyId, $this->contractorId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
