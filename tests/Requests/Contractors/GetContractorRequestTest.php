<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Contractors\Contractor;
use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorAccountSetup;
use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorBanking;
use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorEmergencyContact;
use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorPrimaryContact;
use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorRates;
use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorUserProfile;
use Simpro\PhpSdk\Simpro\Requests\Contractors\GetContractorRequest;

it('sends get contractor request to correct endpoint', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get contractor response correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Contractor::class)
        ->and($dto->id)->toBe(1665)
        ->and($dto->name)->toBe('Bright Spark Electrical Limited')
        ->and($dto->position)->toBe('')
        ->and($dto->dateOfHire)->toBe('2024-09-20')
        ->and($dto->dateOfBirth)->toBeNull()
        ->and($dto->archived)->toBe(false)
        ->and($dto->maskedSSN)->toBeNull()
        ->and($dto->ein)->toBe('')
        ->and($dto->companyNumber)->toBe('12345678')
        ->and($dto->contactName)->toBe('Billy Bright')
        ->and($dto->currency)->toBe('GBP')
        ->and($dto->displayOrder)->toBe(0);
});

it('parses contractor address correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->address)->not->toBeNull()
        ->and($dto->address->address)->toBe('10 Industrial Way')
        ->and($dto->address->city)->toBe('London')
        ->and($dto->address->state)->toBe('')
        ->and($dto->address->postalCode)->toBe('SW1A 1AA')
        ->and($dto->address->country)->toBe('United Kingdom');
});

it('parses contractor primary contact correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->primaryContact)->toBeInstanceOf(ContractorPrimaryContact::class)
        ->and($dto->primaryContact->email)->toBe('test@example.com')
        ->and($dto->primaryContact->workPhone)->toBe('01234 567 890')
        ->and($dto->primaryContact->cellPhone)->toBe('07700 900123');
});

it('parses contractor emergency contact correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->emergencyContact)->toBeInstanceOf(ContractorEmergencyContact::class)
        ->and($dto->emergencyContact->name)->toBe('Billy Bright')
        ->and($dto->emergencyContact->relationship)->toBe('Director');
});

it('parses contractor account setup correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->accountSetup)->toBeInstanceOf(ContractorAccountSetup::class)
        ->and($dto->accountSetup->username)->toBe('employee1665')
        ->and($dto->accountSetup->isMobility)->toBe(false)
        ->and($dto->accountSetup->mobileSecurityGroup)->not->toBeNull()
        ->and($dto->accountSetup->mobileSecurityGroup->id)->toBe(3)
        ->and($dto->accountSetup->mobileSecurityGroup->name)->toBe('Contractors');
});

it('parses contractor user profile correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->userProfile)->toBeInstanceOf(ContractorUserProfile::class)
        ->and($dto->userProfile->isSalesperson)->toBe(false)
        ->and($dto->userProfile->isProjectManager)->toBe(false)
        ->and($dto->userProfile->preferredLanguage)->toBe('en_GB');
});

it('parses contractor banking correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->banking)->toBeInstanceOf(ContractorBanking::class)
        ->and($dto->banking->accountName)->toBe('Bright Spark Electrical')
        ->and($dto->banking->routingNo)->toBe('20-30-40')
        ->and($dto->banking->accountNo)->toBe('12345678')
        ->and($dto->banking->paymentTermId)->toBe(934)
        ->and($dto->banking->paymentTerms)->not->toBeNull()
        ->and($dto->banking->paymentTerms->days)->toBe(0)
        ->and($dto->banking->paymentTerms->type)->toBe('Invoice');
});

it('parses contractor rates correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->rates)->toBeInstanceOf(ContractorRates::class)
        ->and($dto->rates->payRate)->toBe(0.0)
        ->and($dto->rates->employmentCost)->toBe(0.0)
        ->and($dto->rates->overhead)->toBe(0.0)
        ->and($dto->rates->taxCode)->not->toBeNull()
        ->and($dto->rates->taxCode->id)->toBe(13)
        ->and($dto->rates->taxCode->code)->toBe('T1')
        ->and($dto->rates->taxCode->type)->toBe('Single')
        ->and($dto->rates->taxCode->rate)->toBe(23.0);
});

it('parses contractor assigned cost centers correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->assignedCostCenters)->toBeArray()
        ->and($dto->assignedCostCenters)->toHaveCount(2)
        ->and($dto->assignedCostCenters[0]->id)->toBe(500)
        ->and($dto->assignedCostCenters[0]->name)->toBe('P - Electrical')
        ->and($dto->assignedCostCenters[1]->id)->toBe(504)
        ->and($dto->assignedCostCenters[1]->name)->toBe('P - Fire Alarm');
});

it('parses contractor default company correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->defaultCompany)->not->toBeNull()
        ->and($dto->defaultCompany->id)->toBe(198)
        ->and($dto->defaultCompany->name)->toBe('Stitch Digital');
});

it('parses contractor custom fields correctly', function () {
    MockClient::global([
        GetContractorRequest::class => MockResponse::fixture('get_contractor_request'),
    ]);

    $request = new GetContractorRequest(0, 1665);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->customFields)->toBeArray()
        ->and($dto->customFields)->toHaveCount(1)
        ->and($dto->customFields[0]->id)->toBe(354)
        ->and($dto->customFields[0]->name)->toBe('SimForma Account Required')
        ->and($dto->customFields[0]->type)->toBe('List')
        ->and($dto->customFields[0]->value)->toBeNull();
});
