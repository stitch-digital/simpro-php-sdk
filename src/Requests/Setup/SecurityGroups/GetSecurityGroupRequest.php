<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\SecurityGroups;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\SecurityGroup;

final class GetSecurityGroupRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $securityGroupId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/securityGroups/{$this->securityGroupId}";
    }

    public function createDtoFromResponse(Response $response): SecurityGroup
    {
        return SecurityGroup::fromResponse($response);
    }
}
