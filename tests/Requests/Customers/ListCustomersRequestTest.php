<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerListItem;
use Simpro\PhpSdk\Simpro\Requests\Customers\ListCustomersRequest;

it('sends list customers request to correct endpoint', function () {
    MockClient::global([
        ListCustomersRequest::class => MockResponse::fixture('list_customers_request'),
    ]);

    $request = new ListCustomersRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list customers response correctly', function () {
    MockClient::global([
        ListCustomersRequest::class => MockResponse::fixture('list_customers_request'),
    ]);

    $request = new ListCustomersRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->companyName)->toBe('Acme Corp')
        ->and($dto[0]->type)->toBe('Company')
        ->and($dto[1])->toBeInstanceOf(CustomerListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->companyName)->toBe('Smith Industries');
});
