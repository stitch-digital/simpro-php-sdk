<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\TestReading;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\WorkOrderAssetDetailed;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\ListWorkOrderAssetsDetailedRequest;

it('sends list work order assets detailed request to correct endpoint', function () {
    MockClient::global([
        ListWorkOrderAssetsDetailedRequest::class => MockResponse::fixture('list_work_order_assets_detailed_request'),
    ]);

    $request = new ListWorkOrderAssetsDetailedRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1, workOrderId: 99);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes columns query parameter', function () {
    MockClient::global([
        ListWorkOrderAssetsDetailedRequest::class => MockResponse::fixture('list_work_order_assets_detailed_request'),
    ]);

    $request = new ListWorkOrderAssetsDetailedRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1, workOrderId: 99);
    $this->sdk->send($request);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ServiceLevel')
        ->and($query['columns'])->toContain('TestReadings');
});

it('parses list work order assets detailed response correctly', function () {
    MockClient::global([
        ListWorkOrderAssetsDetailedRequest::class => MockResponse::fixture('list_work_order_assets_detailed_request'),
    ]);

    $request = new ListWorkOrderAssetsDetailedRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1, workOrderId: 99);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(WorkOrderAssetDetailed::class)
        ->and($dto[0]->assetId)->toBe(827)
        ->and($dto[0]->assetType)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->assetType->id)->toBe(455)
        ->and($dto[0]->assetType->name)->toBe('Emergency Lighting')
        ->and($dto[0]->serviceLevel)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->serviceLevel->id)->toBe(223)
        ->and($dto[0]->serviceLevel->name)->toBe('Monthly')
        ->and($dto[0]->result)->toBe('Pass')
        ->and($dto[0]->notes)->toBe('TEST')
        ->and($dto[0]->failurePoints)->toBeEmpty()
        ->and($dto[0]->testReadings)->toHaveCount(1)
        ->and($dto[0]->testReadings[0])->toBeInstanceOf(TestReading::class)
        ->and($dto[0]->testReadings[0]->testReading)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->testReadings[0]->testReading->id)->toBe(4164)
        ->and($dto[0]->testReadings[0]->testReading->name)->toBe('State')
        ->and($dto[0]->testReadings[0]->value)->toBeNull();
});
