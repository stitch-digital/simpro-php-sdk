<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Requests\Employees\CustomFields\ListEmployeeCustomFieldsRequest;

it('sends list employee custom fields request to correct endpoint', function () {
    MockClient::global([
        ListEmployeeCustomFieldsRequest::class => MockResponse::fixture('list_employee_custom_fields_request'),
    ]);

    $request = new ListEmployeeCustomFieldsRequest(0, 123);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list employee custom fields response correctly', function () {
    MockClient::global([
        ListEmployeeCustomFieldsRequest::class => MockResponse::fixture('list_employee_custom_fields_request'),
    ]);

    $request = new ListEmployeeCustomFieldsRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomField::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Emergency Contact')
        ->and($dto[0]->type)->toBe('Text')
        ->and($dto[0]->isMandatory)->toBeTrue()
        ->and($dto[0]->value)->toBe('Jane Doe - 555-0199')
        ->and($dto[1])->toBeInstanceOf(CustomField::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Shirt Size')
        ->and($dto[1]->type)->toBe('List')
        ->and($dto[1]->value)->toBe('L');
});
