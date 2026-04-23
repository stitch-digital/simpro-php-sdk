<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Sites\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Sites\Contacts\SiteContact;

final class GetSiteContactRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $siteId,
        private readonly int|string $contactId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/sites/{$this->siteId}/contacts/{$this->contactId}";
    }

    public function createDtoFromResponse(Response $response): SiteContact
    {
        return SiteContact::fromResponse($response);
    }
}
