<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;

final class GetContractorCustomFieldRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorId,
        private readonly int|string $customFieldId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/customFields/{$this->customFieldId}";
    }

    public function createDtoFromResponse(Response $response): CustomField
    {
        return CustomField::fromArray($response->json());
    }
}
