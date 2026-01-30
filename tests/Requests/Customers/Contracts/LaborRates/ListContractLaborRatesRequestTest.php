<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ContractLaborRateListItem;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\LaborRates\ListContractLaborRatesRequest;

it('sends list contract labor rates request to correct endpoint', function () {
    MockClient::global([
        ListContractLaborRatesRequest::class => MockResponse::fixture('list_contract_labor_rates_request'),
    ]);

    $request = new ListContractLaborRatesRequest(0, 123, 100);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list contract labor rates response correctly', function () {
    MockClient::global([
        ListContractLaborRatesRequest::class => MockResponse::fixture('list_contract_labor_rates_request'),
    ]);

    $request = new ListContractLaborRatesRequest(0, 123, 100);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ContractLaborRateListItem::class)
        ->and($dto[0]->laborRate->id)->toBe(1)
        ->and($dto[0]->laborRate->name)->toBe('Standard Labor')
        ->and($dto[0]->isDefault)->toBeTrue()
        ->and($dto[1])->toBeInstanceOf(ContractLaborRateListItem::class)
        ->and($dto[1]->laborRate->id)->toBe(2)
        ->and($dto[1]->laborRate->name)->toBe('Overtime Labor')
        ->and($dto[1]->isDefault)->toBeFalse();
});
