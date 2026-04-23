<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Sites\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Sites\Contacts\SiteContactListItem;

final class ListSiteContactsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $siteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/sites/{$this->siteId}/contacts/";
    }

    /**
     * @return array<SiteContactListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => SiteContactListItem::fromArray($item),
            $data
        );
    }
}
