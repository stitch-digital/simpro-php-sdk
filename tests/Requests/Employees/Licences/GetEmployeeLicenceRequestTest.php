<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Employees\Licences\Licence;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\GetEmployeeLicenceRequest;

it('sends get employee licence request to correct endpoint', function () {
    MockClient::global([
        GetEmployeeLicenceRequest::class => MockResponse::fixture('get_employee_licence_request'),
    ]);

    $request = new GetEmployeeLicenceRequest(0, 123, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get employee licence response correctly', function () {
    MockClient::global([
        GetEmployeeLicenceRequest::class => MockResponse::fixture('get_employee_licence_request'),
    ]);

    $request = new GetEmployeeLicenceRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Licence::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->type->id)->toBe(10)
        ->and($dto->type->name)->toBe('Electrician Licence')
        ->and($dto->licenceNo)->toBe('EL-12345')
        ->and($dto->notes)->toBe('Class A electrical work')
        ->and($dto->isVerified)->toBeTrue()
        ->and($dto->verifiedBy->id)->toBe(5);
});
