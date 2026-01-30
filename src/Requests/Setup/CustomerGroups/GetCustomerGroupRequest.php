<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerGroup;

/**
 * Retrieve details for a specific customer group.
 */
final class GetCustomerGroupRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerGroupId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/customerGroups/{$this->customerGroupId}";
    }

    public function createDtoFromResponse(Response $response): CustomerGroup
    {
        return CustomerGroup::fromResponse($response);
    }
}
