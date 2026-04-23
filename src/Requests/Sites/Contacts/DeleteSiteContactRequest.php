<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Sites\Contacts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteSiteContactRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $siteId,
        private readonly int|string $contactId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/sites/{$this->siteId}/contacts/{$this->contactId}";
    }
}
