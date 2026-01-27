<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Companies\CompanyListItem;
use Simpro\PhpSdk\Simpro\Requests\Companies\ListCompaniesRequest;

it('sends list companies request to correct endpoint', function () {
    MockClient::global([
        ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
    ]);

    $request = new ListCompaniesRequest;
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list companies response correctly', function () {
    MockClient::global([
        ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
    ]);

    $request = new ListCompaniesRequest;
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CompanyListItem::class)
        ->and($dto[0]->id)->toBe(0)
        ->and($dto[0]->name)->toBe('Default Company')
        ->and($dto[1])->toBeInstanceOf(CompanyListItem::class)
        ->and($dto[1]->id)->toBe(1)
        ->and($dto[1]->name)->toBe('Second Company');
});
