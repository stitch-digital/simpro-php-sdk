<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\Zone;
use Simpro\PhpSdk\Simpro\Requests\Setup\Zones\GetZoneRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Zones\ListZonesRequest;

it('sends list zones request to correct endpoint', function () {
    MockClient::global([
        ListZonesRequest::class => MockResponse::fixture('list_zones_request'),
    ]);

    $request = new ListZonesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list zones response correctly', function () {
    MockClient::global([
        ListZonesRequest::class => MockResponse::fixture('list_zones_request'),
    ]);

    $request = new ListZonesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(3)
        ->and($dto[0])->toBeInstanceOf(Zone::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('North Zone')
        ->and($dto[2]->name)->toBe('Central Zone');
});

it('parses get zone response correctly', function () {
    MockClient::global([
        GetZoneRequest::class => MockResponse::fixture('get_zone_request'),
    ]);

    $request = new GetZoneRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Zone::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('North Zone');
});

it('can access zones via setup resource', function () {
    MockClient::global([
        ListZonesRequest::class => MockResponse::fixture('list_zones_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->zones()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get zone via setup resource', function () {
    MockClient::global([
        GetZoneRequest::class => MockResponse::fixture('get_zone_request'),
    ]);

    $zone = $this->sdk->setup(0)->zones()->get(1);

    expect($zone)->toBeInstanceOf(Zone::class)
        ->and($zone->id)->toBe(1);
});
