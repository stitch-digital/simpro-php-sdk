<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\BusinessGroup;

/**
 * Retrieve details for a specific business group.
 */
final class GetBusinessGroupRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $businessGroupId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/businessGroups/{$this->businessGroupId}";
    }

    public function createDtoFromResponse(Response $response): BusinessGroup
    {
        return BusinessGroup::fromResponse($response);
    }
}
