<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Invoices\CreditNotes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\CustomFields\JobCustomFieldValue;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\CustomFields\GetInvoiceCreditNoteCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\CustomFields\ListInvoiceCreditNoteCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\CustomFields\UpdateInvoiceCreditNoteCustomFieldRequest;

/**
 * Resource for managing credit note custom fields.
 *
 * @property AbstractSimproConnector $connector
 */
final class InvoiceCreditNoteCustomFieldResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $invoiceId,
        private readonly int|string $creditNoteId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all custom fields for this credit note.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListInvoiceCreditNoteCustomFieldsRequest($this->companyId, $this->invoiceId, $this->creditNoteId);

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
        $request = new GetInvoiceCreditNoteCustomFieldRequest($this->companyId, $this->invoiceId, $this->creditNoteId, $customFieldId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update a custom field value.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $customFieldId, array $data): Response
    {
        $request = new UpdateInvoiceCreditNoteCustomFieldRequest($this->companyId, $this->invoiceId, $this->creditNoteId, $customFieldId, $data);

        return $this->connector->send($request);
    }
}
