<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a customer group.
 */
final class DeleteCustomerGroupRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerGroupId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/customerGroups/{$this->customerGroupId}";
    }
}
