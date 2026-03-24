<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Invoices\CreditNotes;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Invoices\Notes\InvoiceNote;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\Notes\CreateInvoiceCreditNoteNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\Notes\DeleteInvoiceCreditNoteNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\Notes\GetInvoiceCreditNoteNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\Notes\ListInvoiceCreditNoteNotesRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\Notes\UpdateInvoiceCreditNoteNoteRequest;

/**
 * Resource for managing credit note notes.
 *
 * @property AbstractSimproConnector $connector
 */
final class InvoiceCreditNoteNoteResource extends BaseResource
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
     * List all notes for this credit note.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListInvoiceCreditNoteNotesRequest($this->companyId, $this->invoiceId, $this->creditNoteId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific note.
     */
    public function get(int|string $noteId): InvoiceNote
    {
        $request = new GetInvoiceCreditNoteNoteRequest($this->companyId, $this->invoiceId, $this->creditNoteId, $noteId);

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new note.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created note
     */
    public function create(array $data): int
    {
        $request = new CreateInvoiceCreditNoteNoteRequest($this->companyId, $this->invoiceId, $this->creditNoteId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing note.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $noteId, array $data): Response
    {
        $request = new UpdateInvoiceCreditNoteNoteRequest($this->companyId, $this->invoiceId, $this->creditNoteId, $noteId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a note.
     */
    public function delete(int|string $noteId): Response
    {
        $request = new DeleteInvoiceCreditNoteNoteRequest($this->companyId, $this->invoiceId, $this->creditNoteId, $noteId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple invoice credit note notes in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/invoices/{$this->invoiceId}/creditNotes/{$this->creditNoteId}/notes",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple invoice credit note notes in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/invoices/{$this->invoiceId}/creditNotes/{$this->creditNoteId}/notes",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple invoice credit note notes in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/invoices/{$this->invoiceId}/creditNotes/{$this->creditNoteId}/notes",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
