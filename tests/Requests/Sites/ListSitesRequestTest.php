<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Sites\SiteListItem;
use Simpro\PhpSdk\Simpro\Requests\Sites\ListSitesRequest;

it('sends list sites request to correct endpoint', function () {
    MockClient::global([
        ListSitesRequest::class => MockResponse::fixture('list_sites_request'),
    ]);

    $request = new ListSitesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/sites/');
});

it('parses list sites response correctly', function () {
    MockClient::global([
        ListSitesRequest::class => MockResponse::fixture('list_sites_request'),
    ]);

    $request = new ListSitesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(3)
        ->and($dto[0])->toBeInstanceOf(SiteListItem::class)
        ->and($dto[0]->id)->toBe(31427)
        ->and($dto[0]->name)->toBe('Head Office')
        ->and($dto[1])->toBeInstanceOf(SiteListItem::class)
        ->and($dto[1]->id)->toBe(31840)
        ->and($dto[1]->name)->toBe('AE Test (site) (updated)')
        ->and($dto[2])->toBeInstanceOf(SiteListItem::class)
        ->and($dto[2]->id)->toBe(31874)
        ->and($dto[2]->name)->toBe('Exampleton Fitness');
});
