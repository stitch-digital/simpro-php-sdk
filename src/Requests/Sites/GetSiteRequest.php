<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Sites;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Sites\SiteListDetailedItem;

final class GetSiteRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $siteId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/sites/{$this->siteId}";
    }

    public function createDtoFromResponse(Response $response): SiteListDetailedItem
    {
        return SiteListDetailedItem::fromArray($response->json());
    }
}
