<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ContractListItem;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\ListContractsRequest;

it('sends list contracts request to correct endpoint', function () {
    MockClient::global([
        ListContractsRequest::class => MockResponse::fixture('list_customer_contracts_request'),
    ]);

    $request = new ListContractsRequest(0, 123);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list contracts response correctly', function () {
    MockClient::global([
        ListContractsRequest::class => MockResponse::fixture('list_customer_contracts_request'),
    ]);

    $request = new ListContractsRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ContractListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Annual Maintenance Contract')
        ->and($dto[0]->contractNo)->toBe('CON-001')
        ->and($dto[0]->startDate)->toBe('2024-01-01')
        ->and($dto[0]->endDate)->toBe('2024-12-31')
        ->and($dto[0]->expired)->toBeFalse()
        ->and($dto[1])->toBeInstanceOf(ContractListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Service Level Agreement')
        ->and($dto[1]->contractNo)->toBe('CON-002');
});
