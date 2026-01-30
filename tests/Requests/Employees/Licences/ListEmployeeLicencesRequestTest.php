<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Employees\Licences\LicenceListItem;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\ListEmployeeLicencesRequest;

it('sends list employee licences request to correct endpoint', function () {
    MockClient::global([
        ListEmployeeLicencesRequest::class => MockResponse::fixture('list_employee_licences_request'),
    ]);

    $request = new ListEmployeeLicencesRequest(0, 123);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list employee licences response correctly', function () {
    MockClient::global([
        ListEmployeeLicencesRequest::class => MockResponse::fixture('list_employee_licences_request'),
    ]);

    $request = new ListEmployeeLicencesRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(LicenceListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->type->id)->toBe(10)
        ->and($dto[0]->type->name)->toBe('Electrician Licence')
        ->and($dto[0]->licenceNo)->toBe('EL-12345')
        ->and($dto[0]->isVerified)->toBeTrue()
        ->and($dto[1])->toBeInstanceOf(LicenceListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->isVerified)->toBeFalse();
});
