<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\Contract;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\ListContractsDetailedRequest;

it('sends list contracts detailed request to correct endpoint', function () {
    MockClient::global([
        ListContractsDetailedRequest::class => MockResponse::fixture('list_customer_contracts_detailed_request'),
    ]);

    $request = new ListContractsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes all columns in the request query', function () {
    MockClient::global([
        ListContractsDetailedRequest::class => MockResponse::fixture('list_customer_contracts_detailed_request'),
    ]);

    $request = new ListContractsDetailedRequest(0, 123);
    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('Name')
        ->and($query['columns'])->toContain('StartDate')
        ->and($query['columns'])->toContain('EndDate')
        ->and($query['columns'])->toContain('ContractNo')
        ->and($query['columns'])->toContain('Value')
        ->and($query['columns'])->toContain('Notes')
        ->and($query['columns'])->toContain('Email')
        ->and($query['columns'])->toContain('Archived')
        ->and($query['columns'])->toContain('Expired')
        ->and($query['columns'])->toContain('PricingTier')
        ->and($query['columns'])->toContain('Markup')
        ->and($query['columns'])->toContain('Rates')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('ServiceLevels');
});

it('parses list contracts detailed response correctly', function () {
    MockClient::global([
        ListContractsDetailedRequest::class => MockResponse::fixture('list_customer_contracts_detailed_request'),
    ]);

    $request = new ListContractsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(Contract::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Annual Maintenance')
        ->and($dto[0]->startDate)->toBe('2024-01-01')
        ->and($dto[0]->endDate)->toBe('2024-12-31')
        ->and($dto[0]->contractNo)->toBe('CON-001')
        ->and($dto[0]->value)->toBe(12000.00);
});

it('parses pricing tier correctly', function () {
    MockClient::global([
        ListContractsDetailedRequest::class => MockResponse::fixture('list_customer_contracts_detailed_request'),
    ]);

    $request = new ListContractsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->pricingTier)->not->toBeNull()
        ->and($dto[0]->pricingTier->id)->toBe(1)
        ->and($dto[0]->pricingTier->name)->toBe('Standard')
        ->and($dto[0]->markup)->toBe(15.0);
});

it('parses rates correctly', function () {
    MockClient::global([
        ListContractsDetailedRequest::class => MockResponse::fixture('list_customer_contracts_detailed_request'),
    ]);

    $request = new ListContractsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->rates)->not->toBeNull()
        ->and($dto[0]->rates->serviceFee)->not->toBeNull()
        ->and($dto[0]->rates->serviceFee->id)->toBe(1)
        ->and($dto[0]->rates->serviceFee->name)->toBe('Standard Call-out Fee');
});

it('parses custom fields correctly', function () {
    MockClient::global([
        ListContractsDetailedRequest::class => MockResponse::fixture('list_customer_contracts_detailed_request'),
    ]);

    $request = new ListContractsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customFields)->toBeArray()
        ->and($dto[0]->customFields)->toHaveCount(1)
        ->and($dto[0]->customFields[0]->name)->toBe('Contract Type')
        ->and($dto[0]->customFields[0]->value)->toBe('Full Service')
        ->and($dto[1]->customFields)->toBeArray()
        ->and($dto[1]->customFields)->toBeEmpty();
});

it('parses service levels correctly', function () {
    MockClient::global([
        ListContractsDetailedRequest::class => MockResponse::fixture('list_customer_contracts_detailed_request'),
    ]);

    $request = new ListContractsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->serviceLevels)->toBeArray()
        ->and($dto[0]->serviceLevels)->toHaveCount(1)
        ->and($dto[0]->serviceLevels[0]->serviceLevel->id)->toBe(1)
        ->and($dto[0]->serviceLevels[0]->serviceLevel->name)->toBe('Gold Support')
        ->and($dto[0]->serviceLevels[0]->assetType->id)->toBe(1)
        ->and($dto[0]->serviceLevels[0]->assetType->name)->toBe('HVAC Unit')
        ->and($dto[1]->serviceLevels)->toBeArray()
        ->and($dto[1]->serviceLevels)->toBeEmpty();
});
