<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Companies;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Companies\CompanyListItem;

final class ListCompaniesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/api/v1.0/companies/';
    }

    /**
     * @return array<CompanyListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => CompanyListItem::fromArray($item),
            $data
        );
    }
}
