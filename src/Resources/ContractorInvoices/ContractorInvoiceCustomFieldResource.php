<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\ContractorInvoices;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\CustomFields\GetContractorInvoiceCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\CustomFields\ListContractorInvoiceCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\CustomFields\UpdateContractorInvoiceCustomFieldRequest;

/**
 * Resource for managing contractor invoice custom fields.
 *
 * Supports read and update operations only. Create and delete operations are not supported.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractorInvoiceCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $contractorInvoiceId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this contractor invoice.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorInvoiceCustomFieldsRequest($this->companyId, $this->contractorInvoiceId);

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
        $request = new GetContractorInvoiceCustomFieldRequest($this->companyId, $this->contractorInvoiceId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateContractorInvoiceCustomFieldRequest($this->companyId, $this->contractorInvoiceId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
