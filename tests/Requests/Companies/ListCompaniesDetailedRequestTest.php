<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Companies\Company;
use Simpro\PhpSdk\Simpro\Data\Companies\DefaultLanguage;
use Simpro\PhpSdk\Simpro\Data\Companies\ScheduleFormat;
use Simpro\PhpSdk\Simpro\Requests\Companies\ListCompaniesDetailedRequest;

it('sends list companies detailed request to correct endpoint', function () {
    MockClient::global([
        ListCompaniesDetailedRequest::class => MockResponse::fixture('list_companies_detailed_request'),
    ]);

    $request = new ListCompaniesDetailedRequest;
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes columns parameter in query', function () {
    $request = new ListCompaniesDetailedRequest;
    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('Name')
        ->and($query['columns'])->toContain('Phone')
        ->and($query['columns'])->toContain('DefaultLanguage');
});

it('parses list companies detailed response correctly', function () {
    MockClient::global([
        ListCompaniesDetailedRequest::class => MockResponse::fixture('list_companies_detailed_request'),
    ]);

    $request = new ListCompaniesDetailedRequest;
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(Company::class)
        ->and($dto[0]->id)->toBe(0)
        ->and($dto[0]->name)->toBe('simPRO Software Australia')
        ->and($dto[0]->phone)->toBe('+61 7 3147 8777')
        ->and($dto[0]->email)->toBe('sales@simpro.com.au')
        ->and($dto[0]->timezone)->toBe('Australia/Brisbane')
        ->and($dto[0]->defaultLanguage)->toBe(DefaultLanguage::EN_AU)
        ->and($dto[0]->scheduleFormat)->toBe(ScheduleFormat::FIFTEEN_MINUTES)
        ->and($dto[0]->singleCostCenterMode)->toBeTrue()
        ->and($dto[0]->address->line1)->toBe('Ground floor')
        ->and($dto[0]->banking->bank)->toBe('Test Bank')
        ->and($dto[0]->defaultCostCenter)->not->toBeNull()
        ->and($dto[0]->defaultCostCenter->id)->toBe(1);
});
