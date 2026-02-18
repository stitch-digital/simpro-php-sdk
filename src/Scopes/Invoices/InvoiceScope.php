<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Invoices;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Invoices\InvoiceCreditNoteResource;
use Simpro\PhpSdk\Simpro\Resources\Invoices\InvoiceCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Invoices\InvoiceNoteResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific invoice, providing access to nested resources.
 *
 * @example
 * // Access invoice notes
 * $connector->invoices(companyId: 0)->invoice(invoiceId: 123)->notes()->list();
 *
 * // Access invoice credit notes
 * $connector->invoices(companyId: 0)->invoice(invoiceId: 123)->creditNotes()->list();
 */
final class InvoiceScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $invoiceId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access notes for this invoice.
     */
    public function notes(): InvoiceNoteResource
    {
        return new InvoiceNoteResource($this->connector, $this->companyId, $this->invoiceId);
    }

    /**
     * Access custom fields for this invoice.
     */
    public function customFields(): InvoiceCustomFieldResource
    {
        return new InvoiceCustomFieldResource($this->connector, $this->companyId, $this->invoiceId);
    }

    /**
     * Access credit notes for this invoice.
     */
    public function creditNotes(): InvoiceCreditNoteResource
    {
        return new InvoiceCreditNoteResource($this->connector, $this->companyId, $this->invoiceId);
    }

    /**
     * Navigate to a specific credit note scope.
     */
    public function creditNote(int|string $creditNoteId): InvoiceCreditNoteScope
    {
        return new InvoiceCreditNoteScope($this->connector, $this->companyId, $this->invoiceId, $creditNoteId);
    }
}
