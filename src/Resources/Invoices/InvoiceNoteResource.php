<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Invoices;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Invoices\Notes\InvoiceNote;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Invoices\Notes\CreateInvoiceNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\Notes\DeleteInvoiceNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\Notes\GetInvoiceNoteRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\Notes\ListInvoiceNotesRequest;
use Simpro\PhpSdk\Simpro\Requests\Invoices\Notes\UpdateInvoiceNoteRequest;

/**
 * Resource for managing invoice notes.
 *
 * @property AbstractSimproConnector $connector
 */
final class InvoiceNoteResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $invoiceId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all notes for this invoice.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListInvoiceNotesRequest($this->companyId, $this->invoiceId);

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
        $request = new GetInvoiceNoteRequest($this->companyId, $this->invoiceId, $noteId);

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
        $request = new CreateInvoiceNoteRequest($this->companyId, $this->invoiceId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing note.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $noteId, array $data): Response
    {
        $request = new UpdateInvoiceNoteRequest($this->companyId, $this->invoiceId, $noteId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete a note.
     */
    public function delete(int|string $noteId): Response
    {
        $request = new DeleteInvoiceNoteRequest($this->companyId, $this->invoiceId, $noteId);

        return $this->connector->send($request);
    }
}
