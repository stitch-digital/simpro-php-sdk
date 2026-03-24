<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\WorkOrderAssetReference;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\WorkOrderDetailed;
use Simpro\PhpSdk\Simpro\Data\JobWorkOrders\JobWorkOrderBlock;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\ListWorkOrdersDetailedRequest;

it('sends list work orders detailed request to correct endpoint', function () {
    MockClient::global([
        ListWorkOrdersDetailedRequest::class => MockResponse::fixture('list_work_orders_detailed_request'),
    ]);

    $request = new ListWorkOrdersDetailedRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes columns query parameter', function () {
    MockClient::global([
        ListWorkOrdersDetailedRequest::class => MockResponse::fixture('list_work_orders_detailed_request'),
    ]);

    $request = new ListWorkOrdersDetailedRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1);
    $this->sdk->send($request);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('Blocks')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('WorkOrderAssets');
});

it('excludes Materials column when includeMaterials is false', function () {
    MockClient::global([
        ListWorkOrdersDetailedRequest::class => MockResponse::fixture('list_work_orders_detailed_request'),
    ]);

    $request = new ListWorkOrdersDetailedRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1, includeMaterials: false);
    $this->sdk->send($request);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->not->toContain('Materials');
});

it('parses list work orders detailed response correctly', function () {
    MockClient::global([
        ListWorkOrdersDetailedRequest::class => MockResponse::fixture('list_work_orders_detailed_request'),
    ]);

    $request = new ListWorkOrdersDetailedRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(WorkOrderDetailed::class)
        ->and($dto[0]->id)->toBe(2851)
        ->and($dto[0]->staff)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->staff->id)->toBe(1446)
        ->and($dto[0]->staff->name)->toBe('John Trickett')
        ->and($dto[0]->workOrderDate)->toBe('2025-11-17')
        ->and($dto[0]->descriptionNotes)->toBe('')
        ->and($dto[0]->approved)->toBeTrue()
        ->and($dto[0]->materials)->toBeEmpty()
        ->and($dto[0]->blocks)->toHaveCount(1)
        ->and($dto[0]->blocks[0])->toBeInstanceOf(JobWorkOrderBlock::class)
        ->and($dto[0]->blocks[0]->hrs)->toBe(0.5)
        ->and($dto[0]->blocks[0]->startTime)->toBe('10:30')
        ->and($dto[0]->blocks[0]->iso8601StartTime)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->blocks[0]->endTime)->toBe('11:00')
        ->and($dto[0]->blocks[0]->scheduleRate)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->blocks[0]->scheduleRate->id)->toBe(330)
        ->and($dto[0]->blocks[0]->scheduleRate->name)->toBe('Normal Time')
        ->and($dto[0]->scheduledHrs)->toBe(0.5)
        ->and($dto[0]->scheduledStartTime)->toBe('10:30')
        ->and($dto[0]->iso8601ScheduledStartTime)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->dateModified)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->customFields)->toBeEmpty()
        ->and($dto[0]->workOrderAssets)->toHaveCount(1)
        ->and($dto[0]->workOrderAssets[0])->toBeInstanceOf(WorkOrderAssetReference::class)
        ->and($dto[0]->workOrderAssets[0]->assetId)->toBe(733)
        ->and($dto[0]->workOrderAssets[0]->assetType)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->workOrderAssets[0]->assetType->id)->toBe(410)
        ->and($dto[0]->workOrderAssets[0]->assetType->name)->toBe('Machine Asset')
        ->and($dto[0]->workOrderAssets[0]->result)->toBe('Pass')
        ->and($dto[1])->toBeInstanceOf(WorkOrderDetailed::class)
        ->and($dto[1]->id)->toBe(2850)
        ->and($dto[1]->descriptionNotes)->toContain('work completed by engineers')
        ->and($dto[1]->materialNotes)->toContain('techy notes')
        ->and($dto[1]->scheduledHrs)->toBe(1.5);
});
