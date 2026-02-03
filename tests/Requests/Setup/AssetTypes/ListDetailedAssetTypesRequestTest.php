<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetType;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetTypeServiceLevel;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes\ListDetailedAssetTypesRequest;

it('sends list detailed asset types request to correct endpoint', function () {
    MockClient::global([
        ListDetailedAssetTypesRequest::class => MockResponse::fixture('list_detailed_asset_types_request'),
    ]);

    $request = new ListDetailedAssetTypesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('list detailed asset types request includes columns parameter', function () {
    $request = new ListDetailedAssetTypesRequest(0);
    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('Name')
        ->and($query['columns'])->toContain('ServiceLevels');
});

it('parses list detailed asset types response correctly', function () {
    MockClient::global([
        ListDetailedAssetTypesRequest::class => MockResponse::fixture('list_detailed_asset_types_request'),
    ]);

    $request = new ListDetailedAssetTypesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(AssetType::class)
        ->and($dto[0]->id)->toBe(908)
        ->and($dto[0]->name)->toBe('Battery - VRLA')
        ->and($dto[0]->description)->toBe('Battery - VRLA')
        ->and($dto[0]->archived)->toBe(false)
        ->and($dto[0]->jobCostCenter)->not->toBeNull()
        ->and($dto[0]->jobCostCenter->id)->toBe(711)
        ->and($dto[0]->jobCostCenter->name)->toBe('Service - Agreements');
});

it('parses service levels in detailed response', function () {
    MockClient::global([
        ListDetailedAssetTypesRequest::class => MockResponse::fixture('list_detailed_asset_types_request'),
    ]);

    $request = new ListDetailedAssetTypesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->serviceLevels)->toBeArray()
        ->and($dto[0]->serviceLevels)->toHaveCount(2)
        ->and($dto[0]->serviceLevels[0])->toBeInstanceOf(AssetTypeServiceLevel::class)
        ->and($dto[0]->serviceLevels[0]->serviceLevel->id)->toBe(542)
        ->and($dto[0]->serviceLevels[0]->serviceLevel->name)->toBe('PM Annual')
        ->and($dto[0]->serviceLevels[0]->displayOrder)->toBe(0)
        ->and($dto[0]->serviceLevels[0]->isDefault)->toBe(false)
        ->and($dto[0]->serviceLevels[1]->serviceLevel->id)->toBe(543)
        ->and($dto[0]->serviceLevels[1]->serviceLevel->name)->toBe('PM Semi Annual');
});

it('can list detailed asset types via setup resource', function () {
    MockClient::global([
        ListDetailedAssetTypesRequest::class => MockResponse::fixture('list_detailed_asset_types_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->assetTypes()->listDetailed();

    expect($queryBuilder)->toBeInstanceOf(QueryBuilder::class);
});
