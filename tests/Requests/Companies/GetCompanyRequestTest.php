<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Companies\Company;
use Simpro\PhpSdk\Simpro\Data\Companies\DefaultLanguage;
use Simpro\PhpSdk\Simpro\Data\Companies\ScheduleFormat;
use Simpro\PhpSdk\Simpro\Requests\Companies\GetCompanyRequest;

it('sends get company request to correct endpoint', function () {
    MockClient::global([
        GetCompanyRequest::class => MockResponse::fixture('get_company_request'),
    ]);

    $request = new GetCompanyRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('resolves endpoint with company ID', function () {
    $request = new GetCompanyRequest(5);
    $endpoint = $request->resolveEndpoint();

    expect($endpoint)->toBe('/api/v1.0/companies/5');
});

it('parses get company response correctly', function () {
    MockClient::global([
        GetCompanyRequest::class => MockResponse::fixture('get_company_request'),
    ]);

    $request = new GetCompanyRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Company::class)
        ->and($dto->id)->toBe(0)
        ->and($dto->name)->toBe('simPRO Software Australia')
        ->and($dto->phone)->toBe('+61 7 3147 8777')
        ->and($dto->fax)->toBe('+61 7 3147 8777')
        ->and($dto->email)->toBe('sales@simpro.com.au')
        ->and($dto->website)->toBe('https://simpro.com.au')
        ->and($dto->timezone)->toBe('Australia/Brisbane')
        ->and($dto->timezoneOffset)->toBe('+10:00')
        ->and($dto->defaultLanguage)->toBe(DefaultLanguage::EN_AU)
        ->and($dto->template)->toBeFalse()
        ->and($dto->multiCompanyLabel)->toBeNull()
        ->and($dto->multiCompanyColor)->toBeNull()
        ->and($dto->currency)->toBe('AUD')
        ->and($dto->country)->toBe('Australia')
        ->and($dto->taxName)->toBe('GST')
        ->and($dto->scheduleFormat)->toBe(ScheduleFormat::FIFTEEN_MINUTES)
        ->and($dto->singleCostCenterMode)->toBeTrue();
});

it('parses nested address correctly', function () {
    MockClient::global([
        GetCompanyRequest::class => MockResponse::fixture('get_company_request'),
    ]);

    $request = new GetCompanyRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->address->line1)->toBe('Ground floor')
        ->and($dto->address->line2)->toBe('31 McKechnie Drive')
        ->and($dto->billingAddress->line1)->toBe('Ground floor')
        ->and($dto->billingAddress->line2)->toBe('31 McKechnie Drive');
});

it('parses nested banking correctly', function () {
    MockClient::global([
        GetCompanyRequest::class => MockResponse::fixture('get_company_request'),
    ]);

    $request = new GetCompanyRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->banking->bank)->toBe('Test Bank')
        ->and($dto->banking->branchCode)->toBe('123')
        ->and($dto->banking->accountName)->toBe('Test Account')
        ->and($dto->banking->routingNo)->toBe('456789')
        ->and($dto->banking->accountNo)->toBe('987654321')
        ->and($dto->banking->swiftCode)->toBe('TESTAU99');
});

it('parses default cost center correctly', function () {
    MockClient::global([
        GetCompanyRequest::class => MockResponse::fixture('get_company_request'),
    ]);

    $request = new GetCompanyRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->defaultCostCenter)->not->toBeNull()
        ->and($dto->defaultCostCenter->id)->toBe(1)
        ->and($dto->defaultCostCenter->name)->toBe('Default Cost Center');
});

it('parses date modified correctly', function () {
    MockClient::global([
        GetCompanyRequest::class => MockResponse::fixture('get_company_request'),
    ]);

    $request = new GetCompanyRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->dateModified)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto->dateModified->format('Y-m-d'))->toBe('2024-01-26');
});
