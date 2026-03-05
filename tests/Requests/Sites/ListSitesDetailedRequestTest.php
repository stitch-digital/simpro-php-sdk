<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Address;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Sites\SiteContactReference;
use Simpro\PhpSdk\Simpro\Data\Sites\SiteCustomerReference;
use Simpro\PhpSdk\Simpro\Data\Sites\SiteListDetailedItem;
use Simpro\PhpSdk\Simpro\Data\Sites\SitePrimaryContact;
use Simpro\PhpSdk\Simpro\Data\Sites\SiteRates;
use Simpro\PhpSdk\Simpro\Requests\Sites\ListSitesDetailedRequest;

it('sends list sites detailed request to correct endpoint', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/sites/');
});

it('includes all columns in the request query', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('Name')
        ->and($query['columns'])->toContain('Address')
        ->and($query['columns'])->toContain('BillingAddress')
        ->and($query['columns'])->toContain('BillingContact')
        ->and($query['columns'])->toContain('PrimaryContact')
        ->and($query['columns'])->toContain('PublicNotes')
        ->and($query['columns'])->toContain('PrivateNotes')
        ->and($query['columns'])->toContain('Zone')
        ->and($query['columns'])->toContain('PreferredTechs')
        ->and($query['columns'])->toContain('PreferredTechnicians')
        ->and($query['columns'])->toContain('DateModified')
        ->and($query['columns'])->toContain('Customers')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('Rates');
});

it('parses list sites detailed response correctly', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(SiteListDetailedItem::class)
        ->and($dto[0]->id)->toBe(31427)
        ->and($dto[0]->name)->toBe('Head Office');
});

it('parses address fields correctly', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->address)->toBeInstanceOf(Address::class)
        ->and($dto[0]->address->address)->toBe('Old Street')
        ->and($dto[0]->address->city)->toBe('')
        ->and($dto[0]->address->state)->toBe('Manchester')
        ->and($dto[0]->address->postalCode)->toBe('M10 9KC')
        ->and($dto[0]->address->country)->toBe('United Kingdom')
        ->and($dto[0]->billingAddress)->toBeInstanceOf(Address::class)
        ->and($dto[0]->billingAddress->address)->toBe('');
});

it('parses primary contact correctly', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->primaryContact)->toBeInstanceOf(SitePrimaryContact::class)
        ->and($dto[0]->primaryContact->contact)->toBeInstanceOf(SiteContactReference::class)
        ->and($dto[0]->primaryContact->contact->id)->toBe(10837)
        ->and($dto[0]->primaryContact->contact->givenName)->toBe('Joe')
        ->and($dto[0]->primaryContact->contact->familyName)->toBe('Bloggs')
        ->and($dto[0]->primaryContact->contact->email)->toBe('dave@test.com')
        ->and($dto[0]->primaryContact->givenName)->toBe('Joe')
        ->and($dto[0]->primaryContact->familyName)->toBe('Bloggs')
        ->and($dto[0]->primaryContact->email)->toBe('dave@test.com')
        ->and($dto[0]->primaryContact->position)->toBe('Manager');
});

it('parses zone correctly', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->zone)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->zone->id)->toBe(39)
        ->and($dto[0]->zone->name)->toBe('North');
});

it('parses date modified correctly', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->dateModified)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->dateModified->format('Y-m-d'))->toBe('2024-12-17');
});

it('parses customers correctly', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customers)->toBeArray()
        ->and($dto[0]->customers)->toHaveCount(1)
        ->and($dto[0]->customers[0])->toBeInstanceOf(SiteCustomerReference::class)
        ->and($dto[0]->customers[0]->id)->toBe(14946)
        ->and($dto[0]->customers[0]->companyName)->toBe('Test Titans Club');
});

it('parses custom fields correctly', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customFields)->toBeArray()
        ->and($dto[0]->customFields)->toHaveCount(1)
        ->and($dto[0]->customFields[0])->toBeInstanceOf(CustomField::class)
        ->and($dto[0]->customFields[0]->id)->toBe(459)
        ->and($dto[0]->customFields[0]->name)->toBe('Clearance Data Link')
        ->and($dto[0]->customFields[0]->type)->toBe('Hyperlink')
        ->and($dto[0]->customFields[0]->value)->toBeNull();
});

it('parses rates correctly', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->rates)->toBeInstanceOf(SiteRates::class)
        ->and($dto[0]->rates->serviceFee)->toBeNull();
});

it('parses notes fields correctly', function () {
    MockClient::global([
        ListSitesDetailedRequest::class => MockResponse::fixture('list_sites_detailed_request'),
    ]);

    $request = new ListSitesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->publicNotes)->toBe('')
        ->and($dto[0]->privateNotes)->toBe('')
        ->and($dto[0]->billingContact)->toBe('');
});
