<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a business group.
 */
final class DeleteBusinessGroupRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $businessGroupId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/businessGroups/{$this->businessGroupId}";
    }
}
