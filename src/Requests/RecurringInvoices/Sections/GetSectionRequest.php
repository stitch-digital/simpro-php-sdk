<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\Sections\Section;

final class GetSectionRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $recurringInvoiceId,
        private readonly int|string $sectionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}";
    }

    public function createDtoFromResponse(Response $response): Section
    {
        return Section::fromResponse($response);
    }
}
