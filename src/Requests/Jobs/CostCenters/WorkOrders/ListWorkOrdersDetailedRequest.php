<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\WorkOrderDetailed;

final class ListWorkOrdersDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int $jobId,
        private readonly int $sectionId,
        private readonly int $costCenterId,
        private readonly bool $includeMaterials = true,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/workOrders/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        $columns = [
            'ID', 'Staff', 'WorkOrderDate', 'DescriptionNotes', 'MaterialNotes',
            'Approved', 'Blocks', 'ScheduledHrs',
            'ScheduledStartTime', 'ISO8601ScheduledStartTime',
            'ScheduledEndTime', 'ISO8601ScheduledEndTime',
            'DateModified', 'CustomFields', 'WorkOrderAssets',
        ];

        if ($this->includeMaterials) {
            array_splice($columns, 6, 0, ['Materials']);
        }

        return [
            'columns' => implode(',', $columns),
        ];
    }

    /**
     * @return array<WorkOrderDetailed>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => WorkOrderDetailed::fromArray($item),
            $response->json()
        );
    }
}
