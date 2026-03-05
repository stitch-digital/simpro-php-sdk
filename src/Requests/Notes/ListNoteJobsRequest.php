<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Notes\JobNoteListItem;

final class ListNoteJobsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/notes/jobs/";
    }

    /**
     * @return array<JobNoteListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => JobNoteListItem::fromArray($item),
            $response->json()
        );
    }
}
