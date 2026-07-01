<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class ConvertQuoteRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $quoteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/convert/";
    }

    public function createDtoFromResponse(Response $response): int
    {
        $data = $response->json();

        return (int) $data['ID'];
    }
}
