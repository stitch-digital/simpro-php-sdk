<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteQuoteRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $quoteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}";
    }
}
