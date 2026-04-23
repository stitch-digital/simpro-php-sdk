<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Sites\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Sites\CustomFields\SiteCustomFieldValue;

final class GetSiteCustomFieldRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $siteId,
        private readonly int|string $customFieldId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/sites/{$this->siteId}/customFields/{$this->customFieldId}";
    }

    public function createDtoFromResponse(Response $response): SiteCustomFieldValue
    {
        return SiteCustomFieldValue::fromResponse($response);
    }
}
