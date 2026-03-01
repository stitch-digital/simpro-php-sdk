<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerCompanyListDetailedItem;
use Simpro\PhpSdk\Simpro\Requests\Customers\ListCustomersDetailedRequest;

it('sends list customers detailed request to correct endpoint', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/customers/');
});

it('includes all columns in the request query', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('_href')
        ->and($query['columns'])->toContain('CompanyName')
        ->and($query['columns'])->toContain('GivenName')
        ->and($query['columns'])->toContain('FamilyName')
        ->and($query['columns'])->toContain('Phone')
        ->and($query['columns'])->toContain('Address')
        ->and($query['columns'])->toContain('BillingAddress')
        ->and($query['columns'])->toContain('CustomerType')
        ->and($query['columns'])->toContain('Tags')
        ->and($query['columns'])->toContain('AmountOwing')
        ->and($query['columns'])->toContain('Profile')
        ->and($query['columns'])->toContain('Banking')
        ->and($query['columns'])->toContain('Archived')
        ->and($query['columns'])->toContain('Sites')
        ->and($query['columns'])->toContain('Contracts')
        ->and($query['columns'])->toContain('Contacts')
        ->and($query['columns'])->toContain('ResponseTimes')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('Email')
        ->and($query['columns'])->toContain('DateModified')
        ->and($query['columns'])->toContain('DateCreated');
});

it('parses list customers detailed response correctly', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerCompanyListDetailedItem::class)
        ->and($dto[0]->id)->toBe(5)
        ->and($dto[0]->companyName)->toBe('11 Howard Hotel')
        ->and($dto[0]->href)->toBe('/api/v1.0/companies/0/customers/companies/5')
        ->and($dto[0]->archived)->toBeFalse();
});

it('parses company customer with address fields', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->address)->not->toBeNull()
        ->and($dto[0]->address->address)->toBe('11 Howard Street')
        ->and($dto[0]->address->city)->toBe('New York')
        ->and($dto[0]->address->state)->toBe('NY')
        ->and($dto[0]->billingAddress)->not->toBeNull()
        ->and($dto[0]->billingAddress->address)->toBe('11 Howard Street');
});

it('parses individual customer correctly', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[1]->id)->toBe(10)
        ->and($dto[1]->givenName)->toBe('Jane')
        ->and($dto[1]->familyName)->toBe('Smith')
        ->and($dto[1]->phone)->toBe('555-1234')
        ->and($dto[1]->email)->toBe('jane.smith@email.com')
        ->and($dto[1]->href)->toBe('/api/v1.0/companies/0/customers/individuals/10')
        ->and($dto[1]->amountOwing)->toBe(250.50)
        ->and($dto[1]->hasAmountOwing())->toBeTrue()
        ->and($dto[1]->billingAddress)->toBeNull()
        ->and($dto[1]->profile)->toBeNull()
        ->and($dto[1]->banking)->toBeNull();
});

it('parses profile with account manager correctly', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->profile)->not->toBeNull()
        ->and($dto[0]->profile->accountManager)->not->toBeNull()
        ->and($dto[0]->profile->accountManager->id)->toBe(16)
        ->and($dto[0]->profile->accountManager->name)->toBe('Sarah Glosenger')
        ->and($dto[0]->profile->currency)->not->toBeNull()
        ->and($dto[0]->profile->currency->name)->toBe('US Dollar');
});

it('parses banking details correctly', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->banking)->not->toBeNull()
        ->and($dto[0]->banking->paymentTermID)->toBe(11)
        ->and($dto[0]->banking->paymentTerms)->not->toBeNull()
        ->and($dto[0]->banking->paymentTerms->days)->toBe(30)
        ->and($dto[0]->banking->paymentTerms->type)->toBe('Invoice')
        ->and($dto[0]->banking->creditLimit)->toBe(-1.0)
        ->and($dto[0]->banking->onStop)->toBeFalse()
        ->and($dto[0]->banking->retention)->toBe('exGST')
        ->and($dto[0]->banking->vendorOrderNoRequired)->toBeFalse();
});

it('parses tags correctly', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->tags)->toBeArray()
        ->and($dto[0]->tags)->toHaveCount(0)
        ->and($dto[1]->tags)->toBeArray()
        ->and($dto[1]->tags)->toHaveCount(1)
        ->and($dto[1]->tags[0]->id)->toBe(1)
        ->and($dto[1]->tags[0]->name)->toBe('Residential');
});

it('parses dates correctly', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->dateModified)->not->toBeNull()
        ->and($dto[0]->dateModified->format('Y-m-d'))->toBe('2025-11-15')
        ->and($dto[0]->dateCreated)->not->toBeNull()
        ->and($dto[0]->dateCreated->format('Y-m-d'))->toBe('2025-09-22');
});

it('parses custom fields correctly', function () {
    MockClient::global([
        ListCustomersDetailedRequest::class => MockResponse::fixture('list_customers_detailed_request'),
    ]);

    $request = new ListCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customFields)->toBeArray()
        ->and($dto[0]->customFields)->toHaveCount(1)
        ->and($dto[0]->customFields[0]->id)->toBe(2)
        ->and($dto[0]->customFields[0]->name)->toBe('Sales Tax Exemption #')
        ->and($dto[0]->customFields[0]->value)->toBeNull();
});
