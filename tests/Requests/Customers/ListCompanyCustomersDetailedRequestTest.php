<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerCompanyListDetailedItem;
use Simpro\PhpSdk\Simpro\Requests\Customers\ListCompanyCustomersDetailedRequest;

it('sends list company customers detailed request to correct endpoint', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes all columns in the request query', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('CompanyName')
        ->and($query['columns'])->toContain('GivenName')
        ->and($query['columns'])->toContain('FamilyName')
        ->and($query['columns'])->toContain('Address')
        ->and($query['columns'])->toContain('BillingAddress')
        ->and($query['columns'])->toContain('CustomerType')
        ->and($query['columns'])->toContain('Tags')
        ->and($query['columns'])->toContain('AmountOwing')
        ->and($query['columns'])->toContain('Profile')
        ->and($query['columns'])->toContain('Banking')
        ->and($query['columns'])->toContain('Sites')
        ->and($query['columns'])->toContain('Contracts')
        ->and($query['columns'])->toContain('Contacts')
        ->and($query['columns'])->toContain('ResponseTimes')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('DateModified')
        ->and($query['columns'])->toContain('DateCreated');
});

it('parses list company customers detailed response correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerCompanyListDetailedItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->companyName)->toBe('Acme Corporation')
        ->and($dto[0]->givenName)->toBe('John')
        ->and($dto[0]->familyName)->toBe('Doe')
        ->and($dto[0]->phone)->toBe('555-0100')
        ->and($dto[0]->email)->toBe('contact@acme.com')
        ->and($dto[0]->href)->toBe('/api/v1.0/companies/0/customers/companies/1')
        ->and($dto[1]->givenName)->toBeNull()
        ->and($dto[1]->familyName)->toBeNull();
});

it('parses address fields correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->address)->not->toBeNull()
        ->and($dto[0]->address->address)->toBe('123 Main Street')
        ->and($dto[0]->address->city)->toBe('Sydney')
        ->and($dto[0]->address->state)->toBe('NSW')
        ->and($dto[0]->billingAddress)->not->toBeNull()
        ->and($dto[0]->billingAddress->address)->toBe('PO Box 100');
});

it('parses customer type correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customerType)->toBe('Commercial')
        ->and($dto[1]->customerType)->toBe('Industrial');
});

it('parses tags correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->tags)->toBeArray()
        ->and($dto[0]->tags)->toHaveCount(2)
        ->and($dto[0]->tags[0]->id)->toBe(1)
        ->and($dto[0]->tags[0]->name)->toBe('VIP');
});

it('parses amount owing correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->amountOwing)->toBe(1500.00)
        ->and($dto[0]->hasAmountOwing())->toBeTrue()
        ->and($dto[1]->amountOwing)->toBe(0.0)
        ->and($dto[1]->hasAmountOwing())->toBeFalse();
});

it('parses profile correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->profile)->not->toBeNull()
        ->and($dto[0]->profile->notes)->toBe('Important customer notes')
        ->and($dto[0]->profile->customerProfile)->not->toBeNull()
        ->and($dto[0]->profile->customerProfile->id)->toBe(5)
        ->and($dto[0]->profile->customerProfile->name)->toBe('Gold')
        ->and($dto[0]->profile->customerGroup)->not->toBeNull()
        ->and($dto[0]->profile->customerGroup->id)->toBe(4)
        ->and($dto[0]->profile->customerGroup->name)->toBe('Commercial')
        ->and($dto[1]->profile)->toBeNull();
});

it('parses banking correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->banking)->not->toBeNull()
        ->and($dto[0]->banking->accountName)->toBe('Acme Corporation')
        ->and($dto[0]->banking->routingNo)->toBe('012-345')
        ->and($dto[0]->banking->accountNo)->toBe('123456789')
        ->and($dto[0]->banking->paymentTermID)->toBe(27)
        ->and($dto[0]->banking->paymentTerms)->not->toBeNull()
        ->and($dto[0]->banking->paymentTerms->days)->toBe(30)
        ->and($dto[0]->banking->paymentTerms->type)->toBe('Invoice')
        ->and($dto[0]->banking->creditLimit)->toBe(-1.0)
        ->and($dto[0]->banking->onStop)->toBeFalse()
        ->and($dto[0]->banking->retention)->toBe('incGST')
        ->and($dto[0]->banking->vendorOrderNoRequired)->toBeTrue()
        ->and($dto[1]->banking)->toBeNull();
});

it('parses sites correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->sites)->toBeArray()
        ->and($dto[0]->sites)->toHaveCount(2)
        ->and($dto[0]->sites[0]->id)->toBe(100)
        ->and($dto[0]->sites[0]->name)->toBe('Head Office');
});

it('parses contracts correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->contracts)->toBeArray()
        ->and($dto[0]->contracts)->toHaveCount(1)
        ->and($dto[0]->contracts[0]->id)->toBe(200)
        ->and($dto[0]->contracts[0]->name)->toBe('Annual Maintenance')
        ->and($dto[0]->contracts[0]->startDate)->toBe('2024-01-01')
        ->and($dto[0]->contracts[0]->endDate)->toBe('2024-12-31')
        ->and($dto[0]->contracts[0]->contractNo)->toBe('AM-2024-001')
        ->and($dto[0]->contracts[0]->expired)->toBeFalse();
});

it('parses contacts correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->contacts)->toBeArray()
        ->and($dto[0]->contacts)->toHaveCount(1)
        ->and($dto[0]->contacts[0]->id)->toBe(300)
        ->and($dto[0]->contacts[0]->givenName)->toBe('John')
        ->and($dto[0]->contacts[0]->familyName)->toBe('Smith')
        ->and($dto[0]->contacts[0]->fullName())->toBe('John Smith');
});

it('parses response times correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->responseTimes)->toBeArray()
        ->and($dto[0]->responseTimes)->toHaveCount(1)
        ->and($dto[0]->responseTimes[0]->id)->toBe(1)
        ->and($dto[0]->responseTimes[0]->name)->toBe('4 Hour');
});

it('parses custom fields correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customFields)->toBeArray()
        ->and($dto[0]->customFields)->toHaveCount(1)
        ->and($dto[0]->customFields[0]->id)->toBe(1)
        ->and($dto[0]->customFields[0]->name)->toBe('Account Manager')
        ->and($dto[0]->customFields[0]->value)->toBe('Sarah Jones');
});

it('parses dates correctly', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->dateModified)->not->toBeNull()
        ->and($dto[0]->dateModified->format('Y-m-d'))->toBe('2024-03-15')
        ->and($dto[0]->dateCreated)->not->toBeNull()
        ->and($dto[0]->dateCreated->format('Y-m-d'))->toBe('2020-01-15');
});

it('provides display name helper method', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->displayName())->toBe('Acme Corporation');
});

it('provides archived status helper method', function () {
    MockClient::global([
        ListCompanyCustomersDetailedRequest::class => MockResponse::fixture('list_company_customers_detailed_request'),
    ]);

    $request = new ListCompanyCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->isArchived())->toBeFalse();
});
