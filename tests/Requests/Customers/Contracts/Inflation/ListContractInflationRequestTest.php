<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ContractInflationListItem;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\Inflation\ListContractInflationRequest;

it('sends list contract inflation request to correct endpoint', function () {
    MockClient::global([
        ListContractInflationRequest::class => MockResponse::fixture('list_contract_inflation_request'),
    ]);

    $request = new ListContractInflationRequest(0, 123, 100);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list contract inflation response correctly', function () {
    MockClient::global([
        ListContractInflationRequest::class => MockResponse::fixture('list_contract_inflation_request'),
    ]);

    $request = new ListContractInflationRequest(0, 123, 100);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ContractInflationListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->date)->toBe('2024-01-01')
        ->and($dto[0]->amount)->toBe(3.5)
        ->and($dto[1])->toBeInstanceOf(ContractInflationListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->date)->toBe('2025-01-01')
        ->and($dto[1]->amount)->toBe(4.0);
});
