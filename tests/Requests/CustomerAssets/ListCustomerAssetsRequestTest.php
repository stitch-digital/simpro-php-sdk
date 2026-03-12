<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\CustomerAssets\CustomerAssetListItem;
use Simpro\PhpSdk\Simpro\Data\CustomerAssets\CustomerAssetServiceLevel;
use Simpro\PhpSdk\Simpro\Requests\CustomerAssets\ListCustomerAssetsRequest;

it('sends list customer assets request to correct endpoint', function () {
    MockClient::global([
        ListCustomerAssetsRequest::class => MockResponse::fixture('list_customer_assets_request'),
    ]);

    $request = new ListCustomerAssetsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/customerAssets/');
});

it('parses list customer assets response correctly', function () {
    MockClient::global([
        ListCustomerAssetsRequest::class => MockResponse::fixture('list_customer_assets_request'),
    ]);

    $request = new ListCustomerAssetsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerAssetListItem::class)
        ->and($dto[0]->id)->toBe(66898)
        ->and($dto[1])->toBeInstanceOf(CustomerAssetListItem::class)
        ->and($dto[1]->id)->toBe(115);
});

it('parses asset type correctly', function () {
    MockClient::global([
        ListCustomerAssetsRequest::class => MockResponse::fixture('list_customer_assets_request'),
    ]);

    $request = new ListCustomerAssetsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->assetType)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->assetType->id)->toBe(79)
        ->and($dto[0]->assetType->name)->toBe('Automatic Door Operator');
});

it('parses site correctly', function () {
    MockClient::global([
        ListCustomerAssetsRequest::class => MockResponse::fixture('list_customer_assets_request'),
    ]);

    $request = new ListCustomerAssetsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->site)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->site->id)->toBe(6128)
        ->and($dto[0]->site->name)->toBe('Bowhayes Lodge');
});

it('parses service levels correctly', function () {
    MockClient::global([
        ListCustomerAssetsRequest::class => MockResponse::fixture('list_customer_assets_request'),
    ]);

    $request = new ListCustomerAssetsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->serviceLevels)->toBeArray()
        ->and($dto[0]->serviceLevels)->toHaveCount(1)
        ->and($dto[0]->serviceLevels[0])->toBeInstanceOf(CustomerAssetServiceLevel::class)
        ->and($dto[0]->serviceLevels[0]->id)->toBe(4)
        ->and($dto[0]->serviceLevels[0]->name)->toBe('Yearly')
        ->and($dto[0]->serviceLevels[0]->serviceDate)->toBe('2027-02-28');
});
