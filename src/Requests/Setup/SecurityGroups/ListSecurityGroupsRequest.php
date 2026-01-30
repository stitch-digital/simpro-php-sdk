<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\SecurityGroups;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\SecurityGroupListItem;

final class ListSecurityGroupsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/securityGroups/";
    }

    /**
     * @return array<int, SecurityGroupListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): SecurityGroupListItem => SecurityGroupListItem::fromArray($item),
            $data
        );
    }
}
