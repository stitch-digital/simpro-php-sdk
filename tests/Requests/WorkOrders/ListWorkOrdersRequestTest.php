<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\WorkOrderListItem;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\ListWorkOrdersRequest;

it('sends list work orders request to correct endpoint', function () {
    MockClient::global([
        ListWorkOrdersRequest::class => MockResponse::fixture('list_work_orders_request'),
    ]);

    $request = new ListWorkOrdersRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list work orders response correctly', function () {
    MockClient::global([
        ListWorkOrdersRequest::class => MockResponse::fixture('list_work_orders_request'),
    ]);

    $request = new ListWorkOrdersRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(WorkOrderListItem::class)
        ->and($dto[0]->id)->toBe(2851)
        ->and($dto[0]->staff)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->staff->id)->toBe(1446)
        ->and($dto[0]->staff->name)->toBe('John Trickett')
        ->and($dto[0]->staff->type)->toBe('employee')
        ->and($dto[0]->staff->typeId)->toBe(1446)
        ->and($dto[0]->workOrderDate)->toBe('2025-11-17')
        ->and($dto[1])->toBeInstanceOf(WorkOrderListItem::class)
        ->and($dto[1]->id)->toBe(2850)
        ->and($dto[1]->workOrderDate)->toBe('2025-11-14');
});
