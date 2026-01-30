<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerIndividual;
use Simpro\PhpSdk\Simpro\Requests\Customers\Individuals\ListIndividualCustomersDetailedRequest;

it('sends list individual customers detailed request to correct endpoint', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes all columns in the request query', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('Title')
        ->and($query['columns'])->toContain('GivenName')
        ->and($query['columns'])->toContain('FamilyName')
        ->and($query['columns'])->toContain('PreferredTechs')
        ->and($query['columns'])->toContain('Phone')
        ->and($query['columns'])->toContain('DoNotCall')
        ->and($query['columns'])->toContain('AltPhone')
        ->and($query['columns'])->toContain('Address')
        ->and($query['columns'])->toContain('BillingAddress')
        ->and($query['columns'])->toContain('CustomerType')
        ->and($query['columns'])->toContain('Tags')
        ->and($query['columns'])->toContain('AmountOwing')
        ->and($query['columns'])->toContain('Rates')
        ->and($query['columns'])->toContain('Profile')
        ->and($query['columns'])->toContain('Banking')
        ->and($query['columns'])->toContain('Archived')
        ->and($query['columns'])->toContain('Sites')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('Email')
        ->and($query['columns'])->toContain('DateModified')
        ->and($query['columns'])->toContain('DateCreated')
        ->and($query['columns'])->toContain('CellPhone');
});

it('parses list individual customers detailed response correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerIndividual::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->title)->toBe('Mr')
        ->and($dto[0]->givenName)->toBe('John')
        ->and($dto[0]->familyName)->toBe('Doe')
        ->and($dto[0]->phone)->toBe('555-1000')
        ->and($dto[0]->email)->toBe('john.doe@email.com')
        ->and($dto[0]->cellPhone)->toBe('555-1002');
});

it('parses address fields correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->address)->not->toBeNull()
        ->and($dto[0]->address->address)->toBe('123 Residential St')
        ->and($dto[0]->address->city)->toBe('Sydney')
        ->and($dto[0]->address->state)->toBe('NSW')
        ->and($dto[0]->billingAddress)->not->toBeNull()
        ->and($dto[0]->billingAddress->address)->toBe('PO Box 50')
        ->and($dto[1]->billingAddress)->toBeNull();
});

it('parses preferred techs correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->preferredTechs)->toBeArray()
        ->and($dto[0]->preferredTechs)->toHaveCount(1)
        ->and($dto[0]->preferredTechs[0]->id)->toBe(10)
        ->and($dto[0]->preferredTechs[0]->name)->toBe('Mike Technician')
        ->and($dto[1]->preferredTechs)->toBeArray()
        ->and($dto[1]->preferredTechs)->toBeEmpty();
});

it('parses tags correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->tags)->toBeArray()
        ->and($dto[0]->tags)->toHaveCount(1)
        ->and($dto[0]->tags[0]->id)->toBe(1)
        ->and($dto[0]->tags[0]->name)->toBe('Residential')
        ->and($dto[1]->tags)->toBeArray()
        ->and($dto[1]->tags)->toBeEmpty();
});

it('parses amount owing correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->amountOwing)->toBe(250.00)
        ->and($dto[1]->amountOwing)->toBe(0.0);
});

it('parses profile correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->profile)->not->toBeNull()
        ->and($dto[0]->profile->customerProfile)->not->toBeNull()
        ->and($dto[0]->profile->customerProfile->id)->toBe(1)
        ->and($dto[0]->profile->customerProfile->name)->toBe('Standard Customer')
        ->and($dto[1]->profile)->toBeNull();
});

it('parses banking correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->banking)->not->toBeNull()
        ->and($dto[0]->banking->routingNo)->toBe('111-222')
        ->and($dto[0]->banking->accountNo)->toBe('12345678')
        ->and($dto[0]->banking->accountName)->toBe('John Doe')
        ->and($dto[1]->banking)->toBeNull();
});

it('parses sites correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->sites)->toBeArray()
        ->and($dto[0]->sites)->toHaveCount(1)
        ->and($dto[0]->sites[0]->id)->toBe(50)
        ->and($dto[0]->sites[0]->name)->toBe('Home')
        ->and($dto[1]->sites)->toBeArray()
        ->and($dto[1]->sites)->toBeEmpty();
});

it('parses custom fields correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customFields)->toBeArray()
        ->and($dto[0]->customFields)->toHaveCount(1)
        ->and($dto[0]->customFields[0]->name)->toBe('Membership ID')
        ->and($dto[0]->customFields[0]->value)->toBe('MEM-12345')
        ->and($dto[1]->customFields)->toBeArray()
        ->and($dto[1]->customFields)->toBeEmpty();
});

it('parses dates correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->dateModified)->not->toBeNull()
        ->and($dto[0]->dateModified->format('Y-m-d'))->toBe('2024-03-20')
        ->and($dto[0]->dateCreated)->not->toBeNull()
        ->and($dto[0]->dateCreated->format('Y-m-d'))->toBe('2023-01-15');
});

it('parses do not call flag correctly', function () {
    MockClient::global([
        ListIndividualCustomersDetailedRequest::class => MockResponse::fixture('list_individual_customers_detailed_request'),
    ]);

    $request = new ListIndividualCustomersDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->doNotCall)->toBeFalse()
        ->and($dto[1]->doNotCall)->toBeTrue();
});
