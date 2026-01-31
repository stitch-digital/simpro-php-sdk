<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Schedules;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Schedules\Schedule;

/**
 * List schedules with full details.
 *
 * Uses the columns parameter to request all available fields,
 * returning full Schedule DTOs instead of list items.
 */
final class ListDetailedSchedulesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    private const DETAILED_COLUMNS = [
        'ID',
        'Type',
        'Reference',
        'TotalHours',
        'Notes',
        'Staff',
        'Date',
        'Blocks',
        '_href',
        'DateModified',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/schedules/";
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
     * @return array<Schedule>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => Schedule::fromArray($item),
            $data
        );
    }
}
