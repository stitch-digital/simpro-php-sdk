<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomField;

/**
 * Abstract base class for get custom field requests.
 */
abstract class AbstractGetCustomFieldRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $companyId,
        protected readonly int|string $customFieldId,
    ) {}

    abstract protected function getResourcePath(): string;

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/customFields/{$this->getResourcePath()}/{$this->customFieldId}";
    }

    public function createDtoFromResponse(Response $response): CustomField
    {
        return CustomField::fromResponse($response);
    }
}
