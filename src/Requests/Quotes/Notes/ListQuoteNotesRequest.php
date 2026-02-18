<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Quotes\Notes\QuoteNote;

final class ListQuoteNotesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $quoteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/notes/";
    }

    /**
     * @return array<QuoteNote>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(fn (array $item) => QuoteNote::fromArray($item), $response->json());
    }
}
