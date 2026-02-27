<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Data\JobWorkOrders\JobWorkOrderListItem;
use Simpro\PhpSdk\Simpro\Data\JobWorkOrders\JobWorkOrderProject;
use Simpro\PhpSdk\Simpro\Requests\JobWorkOrders\ListJobWorkOrdersRequest;

it('sends list job work orders request to correct endpoint', function () {
    MockClient::global([
        ListJobWorkOrdersRequest::class => MockResponse::fixture('list_job_work_orders_request'),
    ]);

    $request = new ListJobWorkOrdersRequest(companyId: 0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list job work orders response correctly', function () {
    MockClient::global([
        ListJobWorkOrdersRequest::class => MockResponse::fixture('list_job_work_orders_request'),
    ]);

    $request = new ListJobWorkOrdersRequest(companyId: 0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobWorkOrderListItem::class)
        ->and($dto[0]->id)->toBe(501)
        ->and($dto[0]->staff)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->staff->id)->toBe(10)
        ->and($dto[0]->staff->name)->toBe('John Smith')
        ->and($dto[0]->staff->type)->toBe('employee')
        ->and($dto[0]->staff->typeId)->toBe(10)
        ->and($dto[0]->workOrderDate)->toBe('2026-02-20')
        ->and($dto[0]->project)->toBeInstanceOf(JobWorkOrderProject::class)
        ->and($dto[0]->project->id)->toBe(1001)
        ->and($dto[0]->project->name)->toBe('Office Fitout')
        ->and($dto[0]->project->sectionId)->toBe(5)
        ->and($dto[0]->project->costCenterId)->toBe(12)
        ->and($dto[0]->project->costCenterName)->toBe('Labour')
        ->and($dto[1])->toBeInstanceOf(JobWorkOrderListItem::class)
        ->and($dto[1]->id)->toBe(502)
        ->and($dto[1]->staff->type)->toBe('contractor');
});
