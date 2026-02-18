<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes\Sections;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Quotes\Sections\QuoteSectionListItem;

final class ListQuoteSectionsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $quoteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/sections/";
    }

    /**
     * @return array<QuoteSectionListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(fn (array $item) => QuoteSectionListItem::fromArray($item), $response->json());
    }
}
