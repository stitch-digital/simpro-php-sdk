<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Notes\JobNoteDetailedListItem;

final class ListNoteJobsDetailedRequest extends Request implements Paginatable
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
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID', 'Subject', 'Visibility', 'Job', '_href',
                'Note', 'DateCreated', 'FollowUpDate', 'Attachments',
                'AssignTo', 'SubmittedBy',
            ]),
        ];
    }

    /**
     * @return array<JobNoteDetailedListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => JobNoteDetailedListItem::fromArray($item),
            $response->json()
        );
    }
}
