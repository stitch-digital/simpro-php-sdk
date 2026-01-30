<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerIndividualListItem;
use Simpro\PhpSdk\Simpro\Requests\Customers\Individuals\ListIndividualCustomersRequest;

it('sends list individual customers request to correct endpoint', function () {
    MockClient::global([
        ListIndividualCustomersRequest::class => MockResponse::fixture('list_individual_customers_request'),
    ]);

    $request = new ListIndividualCustomersRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list individual customers response correctly', function () {
    MockClient::global([
        ListIndividualCustomersRequest::class => MockResponse::fixture('list_individual_customers_request'),
    ]);

    $request = new ListIndividualCustomersRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerIndividualListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->givenName)->toBe('John')
        ->and($dto[0]->familyName)->toBe('Smith')
        ->and($dto[1])->toBeInstanceOf(CustomerIndividualListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->givenName)->toBe('Jane')
        ->and($dto[1]->familyName)->toBe('Doe');
});
