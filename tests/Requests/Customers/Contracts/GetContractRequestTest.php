<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\Contract;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\GetContractRequest;

it('sends get contract request to correct endpoint', function () {
    MockClient::global([
        GetContractRequest::class => MockResponse::fixture('get_customer_contract_request'),
    ]);

    $request = new GetContractRequest(0, 123, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get contract response correctly', function () {
    MockClient::global([
        GetContractRequest::class => MockResponse::fixture('get_customer_contract_request'),
    ]);

    $request = new GetContractRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Contract::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Annual Maintenance Contract')
        ->and($dto->contractNo)->toBe('CON-001')
        ->and($dto->startDate)->toBe('2024-01-01')
        ->and($dto->endDate)->toBe('2024-12-31')
        ->and($dto->value)->toBe(12000.00)
        ->and($dto->notes)->toBe('Covers all HVAC equipment on-site')
        ->and($dto->email)->toBe('contracts@company.com')
        ->and($dto->archived)->toBeFalse()
        ->and($dto->expired)->toBeFalse()
        ->and($dto->markup)->toBe(15.0);
});

it('parses contract pricing tier correctly', function () {
    MockClient::global([
        GetContractRequest::class => MockResponse::fixture('get_customer_contract_request'),
    ]);

    $request = new GetContractRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->pricingTier)->not->toBeNull()
        ->and($dto->pricingTier->id)->toBe(1)
        ->and($dto->pricingTier->name)->toBe('Standard Tier')
        ->and($dto->pricingTier->defaultMarkup)->toBe(20.0);
});

it('parses contract rates correctly', function () {
    MockClient::global([
        GetContractRequest::class => MockResponse::fixture('get_customer_contract_request'),
    ]);

    $request = new GetContractRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->rates)->not->toBeNull()
        ->and($dto->rates->serviceFee)->not->toBeNull()
        ->and($dto->rates->serviceFee->id)->toBe(1)
        ->and($dto->rates->serviceFee->name)->toBe('Standard Call-out Fee');
});

it('parses contract custom fields correctly', function () {
    MockClient::global([
        GetContractRequest::class => MockResponse::fixture('get_customer_contract_request'),
    ]);

    $request = new GetContractRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->customFields)->toBeArray()
        ->and($dto->customFields)->toHaveCount(1)
        ->and($dto->customFields[0]->id)->toBe(1)
        ->and($dto->customFields[0]->name)->toBe('Contract Manager')
        ->and($dto->customFields[0]->value)->toBe('John Smith');
});

it('parses contract service levels correctly', function () {
    MockClient::global([
        GetContractRequest::class => MockResponse::fixture('get_customer_contract_request'),
    ]);

    $request = new GetContractRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->serviceLevels)->toBeArray()
        ->and($dto->serviceLevels)->toHaveCount(1)
        ->and($dto->serviceLevels[0]->serviceLevel->id)->toBe(1)
        ->and($dto->serviceLevels[0]->serviceLevel->name)->toBe('Premium Support')
        ->and($dto->serviceLevels[0]->assetType->id)->toBe(1)
        ->and($dto->serviceLevels[0]->assetType->name)->toBe('HVAC Unit');
});
