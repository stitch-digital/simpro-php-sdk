<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\JobWorkOrders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\JobWorkOrders\JobWorkOrderDetailed;

final class ListJobWorkOrdersDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobWorkOrders/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID', 'Staff', 'WorkOrderDate', 'DescriptionNotes', 'MaterialNotes',
                'Approved', 'Materials', 'Blocks', 'ScheduledHrs',
                'ScheduledStartTime', 'ISO8601ScheduledStartTime',
                'ScheduledEndTime', 'ISO8601ScheduledEndTime',
                'DateModified', 'CustomFields', 'Project',
            ]),
        ];
    }

    /**
     * @return array<JobWorkOrderDetailed>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => JobWorkOrderDetailed::fromArray($item),
            $response->json()
        );
    }
}
