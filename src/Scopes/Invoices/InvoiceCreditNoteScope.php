<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Invoices;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Invoices\CreditNotes\InvoiceCreditNoteCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Invoices\CreditNotes\InvoiceCreditNoteNoteResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific credit note under an invoice, providing access to nested resources.
 *
 * @example
 * // Access credit note notes
 * $connector->invoices(0)->invoice(123)->creditNote(456)->notes()->list();
 */
final class InvoiceCreditNoteScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $invoiceId,
        private readonly int|string $creditNoteId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access notes for this credit note.
     */
    public function notes(): InvoiceCreditNoteNoteResource
    {
        return new InvoiceCreditNoteNoteResource($this->connector, $this->companyId, $this->invoiceId, $this->creditNoteId);
    }

    /**
     * Access custom fields for this credit note.
     */
    public function customFields(): InvoiceCreditNoteCustomFieldResource
    {
        return new InvoiceCreditNoteCustomFieldResource($this->connector, $this->companyId, $this->invoiceId, $this->creditNoteId);
    }
}
