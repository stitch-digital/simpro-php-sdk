<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\CustomerAssets\CustomerAssetContract;
use Simpro\PhpSdk\Simpro\Data\CustomerAssets\CustomerAssetLastTest;
use Simpro\PhpSdk\Simpro\Data\CustomerAssets\CustomerAssetListDetailedItem;
use Simpro\PhpSdk\Simpro\Requests\CustomerAssets\ListCustomerAssetsDetailedRequest;

it('sends list customer assets detailed request to correct endpoint', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/customerAssets/');
});

it('includes all columns in the request query', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('AssetType')
        ->and($query['columns'])->toContain('DisplayOrder')
        ->and($query['columns'])->toContain('CustomerContract')
        ->and($query['columns'])->toContain('StartDate')
        ->and($query['columns'])->toContain('LastTest')
        ->and($query['columns'])->toContain('Archived')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('ParentID')
        ->and($query['columns'])->toContain('DateModified')
        ->and($query['columns'])->toContain('Site');
});

it('parses list customer assets detailed response correctly', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerAssetListDetailedItem::class)
        ->and($dto[0]->id)->toBe(66898)
        ->and($dto[0]->displayOrder)->toBe(0)
        ->and($dto[0]->startDate)->toBe('2026-01-08')
        ->and($dto[0]->archived)->toBeFalse()
        ->and($dto[0]->parentId)->toBeNull();
});

it('parses asset type correctly', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->assetType)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->assetType->id)->toBe(79)
        ->and($dto[0]->assetType->name)->toBe('Automatic Door Operator');
});

it('parses last test correctly', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->lastTest)->toBeInstanceOf(CustomerAssetLastTest::class)
        ->and($dto[0]->lastTest->result)->toBe('Pass')
        ->and($dto[0]->lastTest->date)->toBe('2026-03-02')
        ->and($dto[0]->lastTest->serviceLevel)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->lastTest->serviceLevel->id)->toBe(4)
        ->and($dto[0]->lastTest->serviceLevel->name)->toBe('Yearly');
});

it('parses null customer contract correctly', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customerContract)->toBeNull();
});

it('parses customer contract correctly', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[1]->customerContract)->toBeInstanceOf(CustomerAssetContract::class)
        ->and($dto[1]->customerContract->id)->toBe(4790)
        ->and($dto[1]->customerContract->name)->toBe('Worcestershire County Cricket Club - 12 Month Service Contract')
        ->and($dto[1]->customerContract->startDate)->toBe('2019-09-01')
        ->and($dto[1]->customerContract->endDate)->toBeNull()
        ->and($dto[1]->customerContract->contractNo)->toBe('1093')
        ->and($dto[1]->customerContract->expired)->toBeFalse();
});

it('parses custom fields correctly', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customFields)->toBeArray()
        ->and($dto[0]->customFields)->toHaveCount(2)
        ->and($dto[0]->customFields[0])->toBeInstanceOf(CustomField::class)
        ->and($dto[0]->customFields[0]->id)->toBe(219)
        ->and($dto[0]->customFields[0]->name)->toBe('Operator Manufacture')
        ->and($dto[0]->customFields[0]->type)->toBe('List')
        ->and($dto[0]->customFields[0]->value)->toBe('Sesamo');
});

it('parses date modified correctly', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->dateModified)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->dateModified->format('Y-m-d'))->toBe('2026-03-02');
});

it('parses site correctly', function () {
    MockClient::global([
        ListCustomerAssetsDetailedRequest::class => MockResponse::fixture('list_customer_assets_detailed_request'),
    ]);

    $request = new ListCustomerAssetsDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->site)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->site->id)->toBe(6128)
        ->and($dto[0]->site->name)->toBe('Bowhayes Lodge');
});
