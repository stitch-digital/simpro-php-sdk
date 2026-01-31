<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Invoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Invoices\Invoice;

final class GetInvoiceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $invoiceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/invoices/{$this->invoiceId}";
    }

    public function createDtoFromResponse(Response $response): Invoice
    {
        return Invoice::fromResponse($response);
    }
}
