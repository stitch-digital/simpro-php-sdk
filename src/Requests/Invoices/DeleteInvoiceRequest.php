<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Invoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteInvoiceRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $invoiceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/invoices/{$this->invoiceId}";
    }
}
