<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Customer;
use Simpro\PhpSdk\Simpro\Requests\Customers\GetCompanyCustomerRequest;

it('sends get company customer request to correct endpoint', function () {
    MockClient::global([
        GetCompanyCustomerRequest::class => MockResponse::fixture('get_customer_request'),
    ]);

    $request = new GetCompanyCustomerRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get company customer response correctly', function () {
    MockClient::global([
        GetCompanyCustomerRequest::class => MockResponse::fixture('get_customer_request'),
    ]);

    $request = new GetCompanyCustomerRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Customer::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->companyName)->toBe('Acme Corp')
        ->and($dto->customerType)->toBe('Customer')
        ->and($dto->email)->toBe('contact@acme.com')
        ->and($dto->address)->not->toBeNull()
        ->and($dto->address->city)->toBe('Sydney')
        ->and($dto->archived)->toBe(false)
        ->and($dto->doNotCall)->toBe(false)
        ->and($dto->website)->toBe('https://acme.com')
        ->and($dto->fax)->toBe('555-0102')
        ->and($dto->ein)->toBe('12345678901');
});
