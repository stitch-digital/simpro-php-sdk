<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Contacts\ContactListItem;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\ListContactsRequest;

it('sends list contacts request to correct endpoint', function () {
    MockClient::global([
        ListContactsRequest::class => MockResponse::fixture('list_customer_contacts_request'),
    ]);

    $request = new ListContactsRequest(0, 123);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list contacts response correctly', function () {
    MockClient::global([
        ListContactsRequest::class => MockResponse::fixture('list_customer_contacts_request'),
    ]);

    $request = new ListContactsRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ContactListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->givenName)->toBe('Alice')
        ->and($dto[0]->familyName)->toBe('Johnson')
        ->and($dto[0]->email)->toBe('alice.johnson@example.com')
        ->and($dto[0]->phone)->toBe('555-0200')
        ->and($dto[0]->position)->toBe('Manager')
        ->and($dto[1])->toBeInstanceOf(ContactListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->givenName)->toBe('Bob')
        ->and($dto[1]->position)->toBe('Technician');
});
