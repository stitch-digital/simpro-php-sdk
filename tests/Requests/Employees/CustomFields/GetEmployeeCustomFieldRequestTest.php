<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Requests\Employees\CustomFields\GetEmployeeCustomFieldRequest;

it('sends get employee custom field request to correct endpoint', function () {
    MockClient::global([
        GetEmployeeCustomFieldRequest::class => MockResponse::fixture('get_employee_custom_field_request'),
    ]);

    $request = new GetEmployeeCustomFieldRequest(0, 123, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get employee custom field response correctly', function () {
    MockClient::global([
        GetEmployeeCustomFieldRequest::class => MockResponse::fixture('get_employee_custom_field_request'),
    ]);

    $request = new GetEmployeeCustomFieldRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(CustomField::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Emergency Contact')
        ->and($dto->type)->toBe('Text')
        ->and($dto->isMandatory)->toBeTrue()
        ->and($dto->value)->toBe('Jane Doe - 555-0199');
});
