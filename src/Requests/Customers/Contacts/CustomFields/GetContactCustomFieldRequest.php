<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;

final class GetContactCustomFieldRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contactId,
        private readonly int|string $customFieldId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/contacts/{$this->contactId}/customFields/{$this->customFieldId}";
    }

    public function createDtoFromResponse(Response $response): CustomField
    {
        return CustomField::fromArray($response->json());
    }
}
