<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Employees\Employee;
use Simpro\PhpSdk\Simpro\Requests\Employees\GetEmployeeRequest;

it('sends get employee request to correct endpoint', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get employee response correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Employee::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('John Smith')
        ->and($dto->givenName)->toBe('John')
        ->and($dto->familyName)->toBe('Smith')
        ->and($dto->email)->toBe('john.smith@company.com')
        ->and($dto->employeeNo)->toBe('EMP-001')
        ->and($dto->address)->not->toBeNull()
        ->and($dto->address->city)->toBe('Melbourne')
        ->and($dto->isArchived)->toBe(false);
});
