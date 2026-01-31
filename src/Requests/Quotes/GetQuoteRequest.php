<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Quotes\Quote;

final class GetQuoteRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $quoteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}";
    }

    public function createDtoFromResponse(Response $response): Quote
    {
        return Quote::fromResponse($response);
    }
}
