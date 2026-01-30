<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Contacts\Contact;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\ListContactsDetailedRequest;

it('sends list contacts detailed request to correct endpoint', function () {
    MockClient::global([
        ListContactsDetailedRequest::class => MockResponse::fixture('list_customer_contacts_detailed_request'),
    ]);

    $request = new ListContactsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes all columns in the request query', function () {
    MockClient::global([
        ListContactsDetailedRequest::class => MockResponse::fixture('list_customer_contacts_detailed_request'),
    ]);

    $request = new ListContactsDetailedRequest(0, 123);
    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('Title')
        ->and($query['columns'])->toContain('GivenName')
        ->and($query['columns'])->toContain('FamilyName')
        ->and($query['columns'])->toContain('Email')
        ->and($query['columns'])->toContain('WorkPhone')
        ->and($query['columns'])->toContain('Fax')
        ->and($query['columns'])->toContain('CellPhone')
        ->and($query['columns'])->toContain('AltPhone')
        ->and($query['columns'])->toContain('Department')
        ->and($query['columns'])->toContain('Position')
        ->and($query['columns'])->toContain('Notes')
        ->and($query['columns'])->toContain('CustomFields')
        ->and($query['columns'])->toContain('DateModified')
        ->and($query['columns'])->toContain('QuoteContact')
        ->and($query['columns'])->toContain('JobContact')
        ->and($query['columns'])->toContain('InvoiceContact')
        ->and($query['columns'])->toContain('StatementContact')
        ->and($query['columns'])->toContain('PrimaryStatementContact')
        ->and($query['columns'])->toContain('PrimaryInvoiceContact')
        ->and($query['columns'])->toContain('PrimaryJobContact')
        ->and($query['columns'])->toContain('PrimaryQuoteContact');
});

it('parses list contacts detailed response correctly', function () {
    MockClient::global([
        ListContactsDetailedRequest::class => MockResponse::fixture('list_customer_contacts_detailed_request'),
    ]);

    $request = new ListContactsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(Contact::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->title)->toBe('Mr')
        ->and($dto[0]->givenName)->toBe('Alice')
        ->and($dto[0]->familyName)->toBe('Johnson')
        ->and($dto[0]->email)->toBe('alice.johnson@example.com')
        ->and($dto[0]->workPhone)->toBe('555-0200')
        ->and($dto[0]->cellPhone)->toBe('555-0201')
        ->and($dto[0]->department)->toBe('Operations')
        ->and($dto[0]->position)->toBe('Manager');
});

it('parses contact role flags correctly', function () {
    MockClient::global([
        ListContactsDetailedRequest::class => MockResponse::fixture('list_customer_contacts_detailed_request'),
    ]);

    $request = new ListContactsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->quoteContact)->toBeTrue()
        ->and($dto[0]->jobContact)->toBeTrue()
        ->and($dto[0]->invoiceContact)->toBeFalse()
        ->and($dto[0]->statementContact)->toBeFalse()
        ->and($dto[0]->primaryJobContact)->toBeTrue()
        ->and($dto[0]->primaryQuoteContact)->toBeTrue()
        ->and($dto[1]->invoiceContact)->toBeTrue()
        ->and($dto[1]->statementContact)->toBeTrue()
        ->and($dto[1]->primaryInvoiceContact)->toBeTrue()
        ->and($dto[1]->primaryStatementContact)->toBeTrue();
});

it('parses custom fields correctly', function () {
    MockClient::global([
        ListContactsDetailedRequest::class => MockResponse::fixture('list_customer_contacts_detailed_request'),
    ]);

    $request = new ListContactsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->customFields)->toBeArray()
        ->and($dto[0]->customFields)->toHaveCount(1)
        ->and($dto[0]->customFields[0]->name)->toBe('Preferred Contact Method')
        ->and($dto[0]->customFields[0]->value)->toBe('Email')
        ->and($dto[1]->customFields)->toBeArray()
        ->and($dto[1]->customFields)->toBeEmpty();
});

it('parses date modified correctly', function () {
    MockClient::global([
        ListContactsDetailedRequest::class => MockResponse::fixture('list_customer_contacts_detailed_request'),
    ]);

    $request = new ListContactsDetailedRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->dateModified)->not->toBeNull()
        ->and($dto[0]->dateModified->format('Y-m-d'))->toBe('2024-05-15');
});
