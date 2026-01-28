<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Employees\EmployeeListItem;
use Simpro\PhpSdk\Simpro\Requests\Employees\ListEmployeesRequest;

it('sends list employees request to correct endpoint', function () {
    MockClient::global([
        ListEmployeesRequest::class => MockResponse::fixture('list_employees_request'),
    ]);

    $request = new ListEmployeesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list employees response correctly', function () {
    MockClient::global([
        ListEmployeesRequest::class => MockResponse::fixture('list_employees_request'),
    ]);

    $request = new ListEmployeesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(EmployeeListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('John Smith')
        ->and($dto[0]->email)->toBe('john.smith@company.com')
        ->and($dto[1])->toBeInstanceOf(EmployeeListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Jane Doe');
});
