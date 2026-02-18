<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Invoices;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Invoices\CreditNotes\CreditNote;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\CreateInvoiceCreditNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\GetInvoiceCreditNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\ListInvoiceCreditNotesRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\UpdateInvoiceCreditNoteRequest;

/**
 * Resource for managing invoice credit notes.
 *
 * @property AbstractSimproConnector $connector
 */
final class InvoiceCreditNoteResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $invoiceId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all credit notes for this invoice.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListInvoiceCreditNotesRequest($this->companyId, $this->invoiceId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific credit note.
     */
    public function get(int|string $creditNoteId): CreditNote
    {
        $request = new GetInvoiceCreditNoteRequest($this->companyId, $this->invoiceId, $creditNoteId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new credit note.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created credit note
     */
    public function create(array $data): int
    {
        $request = new CreateInvoiceCreditNoteRequest($this->companyId, $this->invoiceId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing credit note.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $creditNoteId, array $data): Response
    {
        $request = new UpdateInvoiceCreditNoteRequest($this->companyId, $this->invoiceId, $creditNoteId, $data);

        return $this->connector->send($request);
    }
}
