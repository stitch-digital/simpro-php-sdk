<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\CustomerInvoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteCustomerInvoiceStatusCodeRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $statusCodeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/customerInvoices/{$this->statusCodeId}";
    }
}
