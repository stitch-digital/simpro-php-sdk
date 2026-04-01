<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs\Notes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Jobs\Notes\JobNote;

/**
 * List job notes with full details.
 *
 * Uses the columns parameter to request all available fields,
 * returning full JobNote DTOs instead of list items.
 */
final class ListDetailedJobNotesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    private const DETAILED_COLUMNS = [
        'ID',
        'Subject',
        'Note',
        'DateCreated',
        'FollowUpDate',
        'Visibility',
        'Attachments',
        'AssignTo',
        'SubmittedBy',
        'Reference',
    ];

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $jobId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/notes/";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<JobNote>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => JobNote::fromArray($item),
            $data
        );
    }
}
