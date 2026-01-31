<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\StatusCodes\Projects;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\ProjectStatusCodeListItem;

final class ListProjectStatusCodesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/statusCodes/projects/";
    }

    /**
     * @return array<int, ProjectStatusCodeListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): ProjectStatusCodeListItem => ProjectStatusCodeListItem::fromArray($item),
            $data
        );
    }
}
