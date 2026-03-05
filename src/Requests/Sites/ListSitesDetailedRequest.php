<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Sites;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Sites\SiteListDetailedItem;

final class ListSitesDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/sites/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'Name',
                'Address',
                'BillingAddress',
                'BillingContact',
                'PrimaryContact',
                'PublicNotes',
                'PrivateNotes',
                'Zone',
                'PreferredTechs',
                'PreferredTechnicians',
                'DateModified',
                'Customers',
                'CustomFields',
                'Rates',
            ]),
        ];
    }

    /**
     * @return array<SiteListDetailedItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => SiteListDetailedItem::fromArray($item),
            $data
        );
    }
}
