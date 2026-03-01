<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Notes\CustomerNoteCustomer;
use Simpro\PhpSdk\Simpro\Data\Notes\CustomerNoteListItem;
use Simpro\PhpSdk\Simpro\Data\Notes\NoteVisibility;
use Simpro\PhpSdk\Simpro\Requests\Notes\ListNoteCustomersRequest;

it('sends list note customers request to correct endpoint', function () {
    MockClient::global([
        ListNoteCustomersRequest::class => MockResponse::fixture('list_note_customers_request'),
    ]);

    $request = new ListNoteCustomersRequest(companyId: 0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list note customers response correctly', function () {
    MockClient::global([
        ListNoteCustomersRequest::class => MockResponse::fixture('list_note_customers_request'),
    ]);

    $request = new ListNoteCustomersRequest(companyId: 0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerNoteListItem::class)
        ->and($dto[0]->id)->toBe(101)
        ->and($dto[0]->subject)->toBe('Initial consultation')
        ->and($dto[0]->visibility)->toBeInstanceOf(NoteVisibility::class)
        ->and($dto[0]->visibility->customer)->toBeTrue()
        ->and($dto[0]->visibility->admin)->toBeFalse()
        ->and($dto[0]->customer)->toBeInstanceOf(CustomerNoteCustomer::class)
        ->and($dto[0]->customer->id)->toBe(201)
        ->and($dto[0]->customer->companyName)->toBe('Acme Corp')
        ->and($dto[0]->customer->givenName)->toBe('John')
        ->and($dto[0]->customer->familyName)->toBe('Doe')
        ->and($dto[0]->href)->toBe('/api/v1.0/companies/0/customers/201/notes/101')
        ->and($dto[1])->toBeInstanceOf(CustomerNoteListItem::class)
        ->and($dto[1]->id)->toBe(102)
        ->and($dto[1]->subject)->toBe('Follow-up required')
        ->and($dto[1]->visibility->customer)->toBeFalse()
        ->and($dto[1]->visibility->admin)->toBeTrue();
});
