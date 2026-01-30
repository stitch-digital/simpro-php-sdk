<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerIndividual;
use Simpro\PhpSdk\Simpro\Requests\Customers\Individuals\GetIndividualCustomerRequest;

it('sends get individual customer request to correct endpoint', function () {
    MockClient::global([
        GetIndividualCustomerRequest::class => MockResponse::fixture('get_individual_customer_request'),
    ]);

    $request = new GetIndividualCustomerRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get individual customer response correctly', function () {
    MockClient::global([
        GetIndividualCustomerRequest::class => MockResponse::fixture('get_individual_customer_request'),
    ]);

    $request = new GetIndividualCustomerRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(CustomerIndividual::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->title)->toBe('Mr')
        ->and($dto->givenName)->toBe('John')
        ->and($dto->familyName)->toBe('Smith')
        ->and($dto->email)->toBe('john.smith@example.com')
        ->and($dto->phone)->toBe('555-0100')
        ->and($dto->doNotCall)->toBeFalse()
        ->and($dto->altPhone)->toBe('555-0199')
        ->and($dto->cellPhone)->toBe('555-0150')
        ->and($dto->customerType)->toBe('Customer')
        ->and($dto->amountOwing)->toBe(1500.00)
        ->and($dto->archived)->toBeFalse()
        ->and($dto->dateCreated)->not->toBeNull()
        ->and($dto->dateModified)->not->toBeNull();
});

it('parses individual customer address correctly', function () {
    MockClient::global([
        GetIndividualCustomerRequest::class => MockResponse::fixture('get_individual_customer_request'),
    ]);

    $request = new GetIndividualCustomerRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->address)->not->toBeNull()
        ->and($dto->address->address)->toBe('123 Main St')
        ->and($dto->address->city)->toBe('Sydney')
        ->and($dto->billingAddress)->not->toBeNull()
        ->and($dto->billingAddress->city)->toBe('Melbourne');
});

it('parses individual customer preferred techs correctly', function () {
    MockClient::global([
        GetIndividualCustomerRequest::class => MockResponse::fixture('get_individual_customer_request'),
    ]);

    $request = new GetIndividualCustomerRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->preferredTechs)->toBeArray()
        ->and($dto->preferredTechs)->toHaveCount(1)
        ->and($dto->preferredTechs[0]->id)->toBe(10)
        ->and($dto->preferredTechs[0]->name)->toBe('Mike Technician')
        ->and($dto->preferredTechs[0]->type)->toBe('employee');
});

it('parses individual customer tags correctly', function () {
    MockClient::global([
        GetIndividualCustomerRequest::class => MockResponse::fixture('get_individual_customer_request'),
    ]);

    $request = new GetIndividualCustomerRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->tags)->toBeArray()
        ->and($dto->tags)->toHaveCount(2)
        ->and($dto->tags[0]->id)->toBe(1)
        ->and($dto->tags[0]->name)->toBe('VIP');
});

it('parses individual customer banking correctly', function () {
    MockClient::global([
        GetIndividualCustomerRequest::class => MockResponse::fixture('get_individual_customer_request'),
    ]);

    $request = new GetIndividualCustomerRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->banking)->not->toBeNull()
        ->and($dto->banking->accountName)->toBe('John Smith')
        ->and($dto->banking->routingNo)->toBe('123456')
        ->and($dto->banking->accountNo)->toBe('789012345')
        ->and($dto->banking->creditLimit)->toBe(10000.00)
        ->and($dto->banking->onStop)->toBeFalse();
});
