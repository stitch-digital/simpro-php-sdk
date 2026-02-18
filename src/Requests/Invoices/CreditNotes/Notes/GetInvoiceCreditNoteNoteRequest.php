<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Invoices\CreditNotes\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Invoices\Notes\InvoiceNote;

final class GetInvoiceCreditNoteNoteRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $invoiceId,
        private readonly int|string $creditNoteId,
        private readonly int|string $noteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/invoices/{$this->invoiceId}/creditNotes/{$this->creditNoteId}/notes/{$this->noteId}";
    }

    public function createDtoFromResponse(Response $response): InvoiceNote
    {
        return InvoiceNote::fromResponse($response);
    }
}
