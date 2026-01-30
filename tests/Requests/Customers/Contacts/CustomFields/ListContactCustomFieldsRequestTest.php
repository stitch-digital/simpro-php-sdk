<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contacts\CustomFields\ListContactCustomFieldsRequest;

it('sends list contact custom fields request to correct endpoint', function () {
    MockClient::global([
        ListContactCustomFieldsRequest::class => MockResponse::fixture('list_contact_custom_fields_request'),
    ]);

    $request = new ListContactCustomFieldsRequest(0, 123, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list contact custom fields response correctly', function () {
    MockClient::global([
        ListContactCustomFieldsRequest::class => MockResponse::fixture('list_contact_custom_fields_request'),
    ]);

    $request = new ListContactCustomFieldsRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomField::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Department')
        ->and($dto[0]->type)->toBe('Text')
        ->and($dto[0]->value)->toBe('Engineering')
        ->and($dto[1])->toBeInstanceOf(CustomField::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Priority')
        ->and($dto[1]->type)->toBe('List')
        ->and($dto[1]->isMandatory)->toBeTrue()
        ->and($dto[1]->listItems)->toBe(['High', 'Medium', 'Low'])
        ->and($dto[1]->value)->toBe('High');
});
