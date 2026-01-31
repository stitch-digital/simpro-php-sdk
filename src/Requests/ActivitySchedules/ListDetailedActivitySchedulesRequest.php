<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ActivitySchedules;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\ActivitySchedules\ActivitySchedule;

/**
 * List activity schedules with full details.
 *
 * Uses the columns parameter to request all available fields,
 * returning full ActivitySchedule DTOs instead of list items.
 */
final class ListDetailedActivitySchedulesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    private const DETAILED_COLUMNS = [
        'ID',
        'TotalHours',
        'Notes',
        'IsLocked',
        'RecurringScheduleID',
        'Staff',
        'Date',
        'Blocks',
        'DateModified',
        'Activity',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/activitySchedules/";
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
     * @return array<ActivitySchedule>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => ActivitySchedule::fromArray($item),
            $data
        );
    }
}
