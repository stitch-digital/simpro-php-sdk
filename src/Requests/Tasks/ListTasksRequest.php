<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Tasks;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskListItem;

final class ListTasksRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/tasks/";
    }

    /**
     * @return array<TaskListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => TaskListItem::fromArray($item),
            $response->json()
        );
    }
}
