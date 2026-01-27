<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Companies\Company;
use Simpro\PhpSdk\Simpro\Data\Companies\CompanyListItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Companies\GetCompanyRequest;
use Simpro\PhpSdk\Simpro\Requests\Companies\ListCompaniesDetailedRequest;
use Simpro\PhpSdk\Simpro\Requests\Companies\ListCompaniesRequest;
use Simpro\PhpSdk\Simpro\Resources\CompanyResource;

it('returns CompanyResource from connector', function () {
    $resource = $this->sdk->companies();

    expect($resource)->toBeInstanceOf(CompanyResource::class);
});

it('list() returns QueryBuilder', function () {
    MockClient::global([
        ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
    ]);

    $builder = $this->sdk->companies()->list();

    expect($builder)->toBeInstanceOf(QueryBuilder::class);
});

it('list() returns CompanyListItem objects via items()', function () {
    MockClient::global([
        ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
    ]);

    $builder = $this->sdk->companies()->list();
    $firstCompany = $builder->items()->current();

    expect($firstCompany)->toBeInstanceOf(CompanyListItem::class)
        ->and($firstCompany->id)->toBe(0)
        ->and($firstCompany->name)->toBe('Default Company');
});

it('listDetailed() returns QueryBuilder', function () {
    MockClient::global([
        ListCompaniesDetailedRequest::class => MockResponse::fixture('list_companies_detailed_request'),
    ]);

    $builder = $this->sdk->companies()->listDetailed();

    expect($builder)->toBeInstanceOf(QueryBuilder::class);
});

it('listDetailed() returns full Company objects via items()', function () {
    MockClient::global([
        ListCompaniesDetailedRequest::class => MockResponse::fixture('list_companies_detailed_request'),
    ]);

    $builder = $this->sdk->companies()->listDetailed();
    $firstCompany = $builder->items()->current();

    expect($firstCompany)->toBeInstanceOf(Company::class)
        ->and($firstCompany->id)->toBe(0)
        ->and($firstCompany->name)->toBe('simPRO Software Australia')
        ->and($firstCompany->phone)->toBe('+61 7 3147 8777')
        ->and($firstCompany->email)->toBe('sales@simpro.com.au')
        ->and($firstCompany->timezone)->toBe('Australia/Brisbane');
});

it('get() returns detailed Company object', function () {
    MockClient::global([
        GetCompanyRequest::class => MockResponse::fixture('get_company_request'),
    ]);

    $company = $this->sdk->companies()->get(0);

    expect($company)->toBeInstanceOf(Company::class)
        ->and($company->id)->toBe(0)
        ->and($company->name)->toBe('simPRO Software Australia');
});

it('getDefault() returns company with ID 0', function () {
    MockClient::global([
        GetCompanyRequest::class => MockResponse::fixture('get_company_request'),
    ]);

    $company = $this->sdk->companies()->getDefault();

    expect($company)->toBeInstanceOf(Company::class)
        ->and($company->id)->toBe(0);
});

it('findByName() returns QueryBuilder', function () {
    MockClient::global([
        ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
    ]);

    $builder = $this->sdk->companies()->findByName('Test Company');

    expect($builder)->toBeInstanceOf(QueryBuilder::class);
});

it('findByNameDetailed() returns QueryBuilder', function () {
    MockClient::global([
        ListCompaniesDetailedRequest::class => MockResponse::fixture('list_companies_detailed_request'),
    ]);

    $builder = $this->sdk->companies()->findByNameDetailed('Test Company');

    expect($builder)->toBeInstanceOf(QueryBuilder::class);
});

it('list() applies filters to query', function () {
    MockClient::global([
        ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
    ]);

    $builder = $this->sdk->companies()->list(['search' => 'any', 'orderby' => 'Name']);

    expect($builder)->toBeInstanceOf(QueryBuilder::class);
});

it('listDetailed() applies filters to query', function () {
    MockClient::global([
        ListCompaniesDetailedRequest::class => MockResponse::fixture('list_companies_detailed_request'),
    ]);

    $builder = $this->sdk->companies()->listDetailed(['search' => 'any']);

    expect($builder)->toBeInstanceOf(QueryBuilder::class);
});
