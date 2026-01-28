<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Jobs\Job;

/**
 * Request to list jobs with all available columns.
 *
 * Returns detailed Job DTOs with full nested data structures.
 * Uses display=all to return all records and columns parameter for full field expansion.
 *
 * Note: Using display=all returns all records without pagination, which is useful
 * for sync operations but should be used carefully for large datasets.
 */
final class ListJobsDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobs/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'Type',
                'Customer',
                'CustomerContract',
                'CustomerContact',
                'AdditionalContacts',
                'Site',
                'SiteContact',
                'OrderNo',
                'RequestNo',
                'Name',
                'Description',
                'Notes',
                'DateIssued',
                'DueDate',
                'DueTime',
                'Tags',
                'Salesperson',
                'ProjectManager',
                'Technicians',
                'Technician',
                'Stage',
                'Status',
                'ResponseTime',
                'IsVariation',
                'LinkedVariations',
                'ConvertedFromQuote',
                'ConvertedFrom',
                'Sections',
                'DateModified',
                'AutoAdjustStatus',
                'IsRetentionEnabled',
                'Total',
                'Totals',
                'CustomFields',
                'STC',
                'CompletedDate',
            ]),
        ];
    }

    /**
     * @return array<Job>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => Job::fromArray($item),
            $data
        );
    }
}
