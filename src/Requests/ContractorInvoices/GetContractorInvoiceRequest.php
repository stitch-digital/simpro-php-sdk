<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ContractorInvoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\ContractorInvoices\ContractorInvoice;

final class GetContractorInvoiceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorInvoiceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractorInvoices/{$this->contractorInvoiceId}";
    }

    public function createDtoFromResponse(Response $response): ContractorInvoice
    {
        return ContractorInvoice::fromResponse($response);
    }
}
