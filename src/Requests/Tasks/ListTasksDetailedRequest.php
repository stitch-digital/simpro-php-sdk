<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Tasks;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskListDetailedItem;

final class ListTasksDetailedRequest extends Request implements Paginatable
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
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'Subject',
                'CreatedBy',
                'AssignedTo',
                'Assignees',
                'AssignedToCustomer',
                'CompletedBy',
                'Associated',
                'IsBillable',
                'ShowOnWorkOrder',
                'EmailNotifications',
                'Description',
                'StartDate',
                'DueDate',
                'CompletedDate',
                'Notes',
                'Status',
                'Priority',
                'Category',
                'Estimated',
                'Actual',
                'ParentTask',
                'SubTasks',
                'CustomFields',
                'PercentComplete',
                'DateModified',
            ]),
        ];
    }

    /**
     * @return array<TaskListDetailedItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => TaskListDetailedItem::fromArray($item),
            $response->json()
        );
    }
}
