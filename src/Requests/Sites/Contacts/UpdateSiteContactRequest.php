<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Sites\Contacts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

final class UpdateSiteContactRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private readonly int $companyId,
        private readonly int|string $siteId,
        private readonly int|string $contactId,
        private readonly array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/sites/{$this->siteId}/contacts/{$this->contactId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
