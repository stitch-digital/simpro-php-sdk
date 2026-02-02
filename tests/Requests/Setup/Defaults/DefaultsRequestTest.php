<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsAccounts;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsFinancial;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsGeneral;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsInvoicing;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsJobs;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsJobsQuotes;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsMandatoryDueDate;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsQuotes;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsQuotesMandatoryDueDate;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsSchedule;
use Simpro\PhpSdk\Simpro\Data\Setup\Defaults\DefaultsSystem;
use Simpro\PhpSdk\Simpro\Requests\Setup\Defaults\GetDefaultsRequest;

it('sends get defaults request to correct endpoint', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $request = new GetDefaultsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get defaults response with all nested objects', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $request = new GetDefaultsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Defaults::class)
        ->and($dto->system)->toBeInstanceOf(DefaultsSystem::class)
        ->and($dto->financial)->toBeInstanceOf(DefaultsFinancial::class)
        ->and($dto->schedule)->toBeInstanceOf(DefaultsSchedule::class);
});

it('parses System.General fields correctly', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $request = new GetDefaultsRequest(0);
    $dto = $this->sdk->send($request)->dto();

    $general = $dto->system->general;

    expect($general)->toBeInstanceOf(DefaultsGeneral::class)
        ->and($general->dateFormat)->toBe('DD/MM/YYYY')
        ->and($general->timeFormat)->toBe('12 Hour')
        ->and($general->thousandsSeparator)->toBe(',')
        ->and($general->negativeNumberFormat)->toBe('-X');
});

it('parses System.JobsQuotes fields correctly with Reference', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $request = new GetDefaultsRequest(0);
    $dto = $this->sdk->send($request)->dto();

    $jobsQuotes = $dto->system->jobsQuotes;

    expect($jobsQuotes)->toBeInstanceOf(DefaultsJobsQuotes::class)
        ->and($jobsQuotes->defaultCostCenter)->toBeInstanceOf(Reference::class)
        ->and($jobsQuotes->defaultCostCenter->id)->toBe(1)
        ->and($jobsQuotes->defaultCostCenter->name)->toBe('Labor')
        ->and($jobsQuotes->singleCostCenter)->toBeFalse();
});

it('parses System.Jobs fields correctly', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $request = new GetDefaultsRequest(0);
    $dto = $this->sdk->send($request)->dto();

    $jobs = $dto->system->jobs;

    expect($jobs)->toBeInstanceOf(DefaultsJobs::class)
        ->and($jobs->warrantyCostCenter)->toBeInstanceOf(Reference::class)
        ->and($jobs->warrantyCostCenter->id)->toBe(2)
        ->and($jobs->warrantyCostCenter->name)->toBe('Warranty')
        ->and($jobs->mandatoryDueDateOnCreation)->toBeInstanceOf(DefaultsMandatoryDueDate::class)
        ->and($jobs->mandatoryDueDateOnCreation->serviceJob)->toBeTrue()
        ->and($jobs->mandatoryDueDateOnCreation->projectJob)->toBeFalse();
});

it('parses System.Quotes fields correctly', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $request = new GetDefaultsRequest(0);
    $dto = $this->sdk->send($request)->dto();

    $quotes = $dto->system->quotes;

    expect($quotes)->toBeInstanceOf(DefaultsQuotes::class)
        ->and($quotes->mandatoryDueDateOnCreation)->toBeInstanceOf(DefaultsQuotesMandatoryDueDate::class)
        ->and($quotes->mandatoryDueDateOnCreation->serviceQuote)->toBeFalse()
        ->and($quotes->mandatoryDueDateOnCreation->projectQuote)->toBeTrue();
});

it('parses Financial.Accounts fields correctly', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $request = new GetDefaultsRequest(0);
    $dto = $this->sdk->send($request)->dto();

    $accounts = $dto->financial->accounts;

    expect($accounts)->toBeInstanceOf(DefaultsAccounts::class)
        ->and($accounts->incomeAccount)->toBe('4000')
        ->and($accounts->depositAccount)->toBe('2100')
        ->and($accounts->expenseAccount)->toBe('5000')
        ->and($accounts->contractorInvoiceAccount)->toBe('5100')
        ->and($accounts->retainageAssetAccount)->toBe('1400')
        ->and($accounts->retainageLiabilityAccount)->toBe('2200')
        ->and($accounts->financeChargeAccount)->toBe('4100')
        ->and($accounts->freightAccount)->toBe('5200')
        ->and($accounts->restockingFeeAccount)->toBe('4200')
        ->and($accounts->taxAccount)->toBe('2300');
});

it('parses Financial.Invoicing fields correctly', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $request = new GetDefaultsRequest(0);
    $dto = $this->sdk->send($request)->dto();

    $invoicing = $dto->financial->invoicing;

    expect($invoicing)->toBeInstanceOf(DefaultsInvoicing::class)
        ->and($invoicing->showSellCostPrices)->toBe('Sell')
        ->and($invoicing->financeChargeLabel)->toBe('Finance Charge')
        ->and($invoicing->tracking)->toBe('Invoice')
        ->and($invoicing->retainageHold)->toBe('None');
});

it('parses Schedule fields correctly', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $request = new GetDefaultsRequest(0);
    $dto = $this->sdk->send($request)->dto();

    $schedule = $dto->schedule;

    expect($schedule)->toBeInstanceOf(DefaultsSchedule::class)
        ->and($schedule->workWeekStart)->toBe('Monday')
        ->and($schedule->scheduleFormat)->toBe('30');
});

it('can access defaults via setup resource', function () {
    MockClient::global([
        GetDefaultsRequest::class => MockResponse::fixture('get_defaults_request'),
    ]);

    $defaults = $this->sdk->setup(0)->defaults()->get();

    expect($defaults)->toBeInstanceOf(Defaults::class)
        ->and($defaults->system)->toBeInstanceOf(DefaultsSystem::class)
        ->and($defaults->financial)->toBeInstanceOf(DefaultsFinancial::class)
        ->and($defaults->schedule)->toBeInstanceOf(DefaultsSchedule::class);
});
