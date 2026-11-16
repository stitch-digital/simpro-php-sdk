<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\RecurringInvoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteRecurringInvoiceRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $recurringInvoiceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}";
    }
}
