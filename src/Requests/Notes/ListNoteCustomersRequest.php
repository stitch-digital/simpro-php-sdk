<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Notes\CustomerNoteListItem;

final class ListNoteCustomersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/notes/customers/";
    }

    /**
     * @return array<CustomerNoteListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => CustomerNoteListItem::fromArray($item),
            $response->json()
        );
    }
}
