<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Invoices\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteInvoiceNoteRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $invoiceId,
        private readonly int|string $noteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/invoices/{$this->invoiceId}/notes/{$this->noteId}";
    }
}
