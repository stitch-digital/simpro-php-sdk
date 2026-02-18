<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Invoices\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Jobs\CustomFields\JobCustomFieldValue;

final class GetInvoiceCustomFieldRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $invoiceId,
        private readonly int|string $customFieldId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/invoices/{$this->invoiceId}/customFields/{$this->customFieldId}";
    }

    public function createDtoFromResponse(Response $response): JobCustomFieldValue
    {
        return JobCustomFieldValue::fromResponse($response);
    }
}
