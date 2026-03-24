<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\WorkOrderAsset;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\ListWorkOrderAssetsRequest;

it('sends list work order assets request to correct endpoint', function () {
    MockClient::global([
        ListWorkOrderAssetsRequest::class => MockResponse::fixture('list_work_order_assets_request'),
    ]);

    $request = new ListWorkOrderAssetsRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1, workOrderId: 99);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list work order assets response correctly', function () {
    MockClient::global([
        ListWorkOrderAssetsRequest::class => MockResponse::fixture('list_work_order_assets_request'),
    ]);

    $request = new ListWorkOrderAssetsRequest(companyId: 0, jobId: 1, sectionId: 1, costCenterId: 1, workOrderId: 99);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(WorkOrderAsset::class)
        ->and($dto[0]->assetId)->toBe(827)
        ->and($dto[0]->assetType)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->assetType->id)->toBe(455)
        ->and($dto[0]->assetType->name)->toBe('Emergency Lighting')
        ->and($dto[0]->result)->toBe('Pass');
});
