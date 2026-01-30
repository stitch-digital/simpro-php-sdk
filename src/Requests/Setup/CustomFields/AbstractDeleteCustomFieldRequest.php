<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Abstract base class for delete custom field requests.
 */
abstract class AbstractDeleteCustomFieldRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int|string $companyId,
        protected readonly int|string $customFieldId,
    ) {}

    abstract protected function getResourcePath(): string;

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/customFields/{$this->getResourcePath()}/{$this->customFieldId}";
    }
}
