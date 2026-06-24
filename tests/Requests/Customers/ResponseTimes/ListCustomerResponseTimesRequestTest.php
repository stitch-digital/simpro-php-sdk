<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\ResponseTimes\CustomerResponseTimeListItem;
use Simpro\PhpSdk\Simpro\Requests\Customers\ResponseTimes\ListCustomerResponseTimesRequest;

it('sends list customer response times request to correct endpoint', function () {
    MockClient::global([
        ListCustomerResponseTimesRequest::class => MockResponse::fixture('list_customer_response_times_request'),
    ]);

    $request = new ListCustomerResponseTimesRequest(0, 6588);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list customer response times response correctly', function () {
    MockClient::global([
        ListCustomerResponseTimesRequest::class => MockResponse::fixture('list_customer_response_times_request'),
    ]);

    $request = new ListCustomerResponseTimesRequest(0, 6588);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerResponseTimeListItem::class)
        ->and($dto[0]->responseTime->id)->toBe(12)
        ->and($dto[0]->responseTime->name)->toBe('4 Hours')
        ->and($dto[0]->days)->toBe(0)
        ->and($dto[0]->hours)->toBe(4)
        ->and($dto[0]->minutes)->toBe(0)
        ->and($dto[1])->toBeInstanceOf(CustomerResponseTimeListItem::class)
        ->and($dto[1]->responseTime->id)->toBe(26)
        ->and($dto[1]->responseTime->name)->toBe('P6 - 7 Days')
        ->and($dto[1]->days)->toBe(7)
        ->and($dto[1]->hours)->toBe(0)
        ->and($dto[1]->minutes)->toBe(0);
});
