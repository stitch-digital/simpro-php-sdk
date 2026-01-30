<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\CustomFields\ListContractCustomFieldsRequest;

it('sends list contract custom fields request to correct endpoint', function () {
    MockClient::global([
        ListContractCustomFieldsRequest::class => MockResponse::fixture('list_contract_custom_fields_request'),
    ]);

    $request = new ListContractCustomFieldsRequest(0, 123, 100);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list contract custom fields response correctly', function () {
    MockClient::global([
        ListContractCustomFieldsRequest::class => MockResponse::fixture('list_contract_custom_fields_request'),
    ]);

    $request = new ListContractCustomFieldsRequest(0, 123, 100);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomField::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Contract Type')
        ->and($dto[0]->type)->toBe('List')
        ->and($dto[0]->isMandatory)->toBeTrue()
        ->and($dto[0]->listItems)->toBe(['Maintenance', 'Service', 'Support'])
        ->and($dto[0]->value)->toBe('Maintenance')
        ->and($dto[1])->toBeInstanceOf(CustomField::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('SLA Reference')
        ->and($dto[1]->type)->toBe('Text')
        ->and($dto[1]->value)->toBe('SLA-2024-001');
});
