<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Employees\Employee;
use Simpro\PhpSdk\Simpro\Data\Employees\EmployeeAccountSetup;
use Simpro\PhpSdk\Simpro\Data\Employees\EmployeeBanking;
use Simpro\PhpSdk\Simpro\Data\Employees\EmployeeEmergencyContact;
use Simpro\PhpSdk\Simpro\Data\Employees\EmployeePayRates;
use Simpro\PhpSdk\Simpro\Data\Employees\EmployeePrimaryContact;
use Simpro\PhpSdk\Simpro\Data\Employees\EmployeeUserProfile;
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
        ->and($dto->position)->toBe('Technician')
        ->and($dto->availability)->toBe(['Mon-Fri 9am-5pm', 'Weekends on-call'])
        ->and($dto->dateOfHire)->toBe('2020-01-10')
        ->and($dto->dateOfBirth)->toBe('1985-06-15')
        ->and($dto->archived)->toBe(false)
        ->and($dto->maskedSSN)->toBe('XXX-XX-1234');
});

it('parses employee address correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->address)->not->toBeNull()
        ->and($dto->address->address)->toBe('456 Worker Lane')
        ->and($dto->address->city)->toBe('Melbourne')
        ->and($dto->address->state)->toBe('VIC')
        ->and($dto->address->postalCode)->toBe('3000')
        ->and($dto->address->country)->toBe('Australia');
});

it('parses employee primary contact correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->primaryContact)->toBeInstanceOf(EmployeePrimaryContact::class)
        ->and($dto->primaryContact->email)->toBe('john.smith@company.com')
        ->and($dto->primaryContact->secondaryEmail)->toBe('john.personal@email.com')
        ->and($dto->primaryContact->workPhone)->toBe('555-1001')
        ->and($dto->primaryContact->extension)->toBe('101')
        ->and($dto->primaryContact->cellPhone)->toBe('555-1011')
        ->and($dto->primaryContact->fax)->toBe('555-1002')
        ->and($dto->primaryContact->preferredNotificationMethod)->toBe('Email');
});

it('parses employee emergency contact correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->emergencyContact)->toBeInstanceOf(EmployeeEmergencyContact::class)
        ->and($dto->emergencyContact->name)->toBe('Jane Smith')
        ->and($dto->emergencyContact->relationship)->toBe('Spouse')
        ->and($dto->emergencyContact->workPhone)->toBe('555-2001')
        ->and($dto->emergencyContact->cellPhone)->toBe('555-2011')
        ->and($dto->emergencyContact->altPhone)->toBe('555-2021');
});

it('parses employee account setup correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->accountSetup)->toBeInstanceOf(EmployeeAccountSetup::class)
        ->and($dto->accountSetup->username)->toBe('john.smith')
        ->and($dto->accountSetup->isMobility)->toBe(true)
        ->and($dto->accountSetup->securityGroup)->not->toBeNull()
        ->and($dto->accountSetup->securityGroup->id)->toBe(1)
        ->and($dto->accountSetup->securityGroup->name)->toBe('Technicians');
});

it('parses employee user profile correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->userProfile)->toBeInstanceOf(EmployeeUserProfile::class)
        ->and($dto->userProfile->isSalesperson)->toBe(false)
        ->and($dto->userProfile->isProjectManager)->toBe(false)
        ->and($dto->userProfile->preferredLanguage)->toBe('en_AU')
        ->and($dto->userProfile->storageDevice)->not->toBeNull()
        ->and($dto->userProfile->storageDevice->id)->toBe(1);
});

it('parses employee banking correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->banking)->toBeInstanceOf(EmployeeBanking::class)
        ->and($dto->banking->accountName)->toBe('John Smith')
        ->and($dto->banking->routingNo)->toBe('123456')
        ->and($dto->banking->accountNo)->toBe('789012345');
});

it('parses employee pay rates correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->payRates)->toBeInstanceOf(EmployeePayRates::class)
        ->and($dto->payRates->payRate)->toBe(45.00)
        ->and($dto->payRates->employmentCost)->toBe(55.00)
        ->and($dto->payRates->overhead)->toBe(15.00);
});

it('parses employee assigned cost centers correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->assignedCostCenters)->toBeArray()
        ->and($dto->assignedCostCenters)->toHaveCount(2)
        ->and($dto->assignedCostCenters[0]->id)->toBe(1)
        ->and($dto->assignedCostCenters[0]->name)->toBe('Service')
        ->and($dto->assignedCostCenters[1]->id)->toBe(2)
        ->and($dto->assignedCostCenters[1]->name)->toBe('Installation');
});

it('parses employee zones correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->zones)->toBeArray()
        ->and($dto->zones)->toHaveCount(2)
        ->and($dto->defaultZone)->not->toBeNull()
        ->and($dto->defaultZone->id)->toBe(1)
        ->and($dto->defaultZone->name)->toBe('North')
        ->and($dto->defaultCompany)->not->toBeNull()
        ->and($dto->defaultCompany->id)->toBe(0)
        ->and($dto->defaultCompany->name)->toBe('Main Company');
});

it('parses employee custom fields correctly', function () {
    MockClient::global([
        GetEmployeeRequest::class => MockResponse::fixture('get_employee_request'),
    ]);

    $request = new GetEmployeeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->customFields)->toBeArray()
        ->and($dto->customFields)->toHaveCount(1)
        ->and($dto->customFields[0]->id)->toBe(1)
        ->and($dto->customFields[0]->name)->toBe('Certification Level')
        ->and($dto->customFields[0]->type)->toBe('List')
        ->and($dto->customFields[0]->value)->toBe('Senior');
});
