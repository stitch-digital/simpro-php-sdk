<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Invoices;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CustomFields\JobCustomFieldValue;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CustomFields\GetInvoiceCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CustomFields\ListInvoiceCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CustomFields\UpdateInvoiceCustomFieldRequest;

/**
 * Resource for managing invoice custom fields.
 *
 * @property AbstractSimproConnector $connector
 */
final class InvoiceCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $invoiceId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this invoice.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListInvoiceCustomFieldsRequest($this->companyId, $this->invoiceId);

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
    public function get(int|string $customFieldId): JobCustomFieldValue
    {
        $request = new GetInvoiceCustomFieldRequest($this->companyId, $this->invoiceId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateInvoiceCustomFieldRequest($this->companyId, $this->invoiceId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
