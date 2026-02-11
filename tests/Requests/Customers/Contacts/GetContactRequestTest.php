<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Contacts\Contact;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\GetContactRequest;

it('sends get contact request to correct endpoint', function () {
    MockClient::global([
        GetContactRequest::class => MockResponse::fixture('get_customer_contact_request'),
    ]);

    $request = new GetContactRequest(0, 123, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get contact response correctly', function () {
    MockClient::global([
        GetContactRequest::class => MockResponse::fixture('get_customer_contact_request'),
    ]);

    $request = new GetContactRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Contact::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->contact)->not->toBeNull()
        ->and($dto->contact->id)->toBe(50)
        ->and($dto->contact->givenName)->toBe('Alice')
        ->and($dto->contact->familyName)->toBe('Johnson')
        ->and($dto->contact->email)->toBe('alice.johnson@example.com')
        ->and($dto->title)->toBe('Mr')
        ->and($dto->givenName)->toBe('Alice')
        ->and($dto->familyName)->toBe('Johnson')
        ->and($dto->email)->toBe('alice.johnson@example.com')
        ->and($dto->workPhone)->toBe('555-0200')
        ->and($dto->fax)->toBe('555-0298')
        ->and($dto->cellPhone)->toBe('555-0201')
        ->and($dto->altPhone)->toBe('555-0299')
        ->and($dto->department)->toBe('Operations')
        ->and($dto->position)->toBe('Manager')
        ->and($dto->notes)->toBe('Primary contact for all service requests')
        ->and($dto->customFields)->toBeArray()
        ->and($dto->customFields)->toHaveCount(1)
        ->and($dto->customFields[0]->name)->toBe('Preferred Contact Method')
        ->and($dto->customFields[0]->value)->toBe('Email')
        ->and($dto->dateModified)->not->toBeNull()
        ->and($dto->quoteContact)->toBeTrue()
        ->and($dto->jobContact)->toBeTrue()
        ->and($dto->invoiceContact)->toBeFalse()
        ->and($dto->statementContact)->toBeFalse()
        ->and($dto->primaryStatementContact)->toBeFalse()
        ->and($dto->primaryInvoiceContact)->toBeFalse()
        ->and($dto->primaryJobContact)->toBeTrue()
        ->and($dto->primaryQuoteContact)->toBeTrue();
});
