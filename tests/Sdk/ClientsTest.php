<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Clients\Sector;
use Simpro\PhpSdk\Simpro\Requests\Auth\GetAccessTokenRequest;
use Simpro\PhpSdk\Simpro\Requests\Clients\ListClientsRequest;

it('lists clients via SDK method', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->clients()->list();

    expect($paginator)->toBeInstanceOf(Simpro\PhpSdk\Simpro\Paginators\SimproPaginator::class);
});

it('iterates over paginated clients', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->clients()->list();
    $clients = iterator_to_array($paginator->items());

    expect($clients)->toHaveCount(2)
        ->and($clients[0])->toBeInstanceOf(Simpro\PhpSdk\Simpro\Data\Clients\Client::class)
        ->and($clients[0]->id)->toBe('1')
        ->and($clients[0]->attributes->name)->toBe('Test Client 1')
        ->and($clients[1]->id)->toBe('2')
        ->and($clients[1]->attributes->name)->toBe('Test Client 2');
});

it('supports custom pagination parameters', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->clients()->list();

    expect($paginator)->toBeInstanceOf(Simpro\PhpSdk\Simpro\Paginators\SimproPaginator::class);
});

it('parses DateTime fields correctly', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->clients()->list();
    $clients = iterator_to_array($paginator->items());

    expect($clients[0]->attributes->created)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($clients[0]->attributes->updated)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($clients[0]->attributes->created->format('Y-m-d'))->toBe('2024-01-01')
        ->and($clients[0]->attributes->updated->format('Y-m-d'))->toBe('2024-01-02');
});

it('filters clients by sector enum', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->clients()->whereSector(Sector::Construction);

    expect($paginator)->toBeInstanceOf(Simpro\PhpSdk\Simpro\Paginators\SimproPaginator::class);
});

it('filters clients by sector enum array', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->clients()->whereSector([Sector::Construction, Sector::RetailTrade]);

    expect($paginator)->toBeInstanceOf(Simpro\PhpSdk\Simpro\Paginators\SimproPaginator::class);
});

it('filters clients by sector string for backward compatibility', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->clients()->whereSector('Construction');

    expect($paginator)->toBeInstanceOf(Simpro\PhpSdk\Simpro\Paginators\SimproPaginator::class);
});

it('excludes clients by sector enum', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->clients()->whereNotSector(Sector::Construction);

    expect($paginator)->toBeInstanceOf(Simpro\PhpSdk\Simpro\Paginators\SimproPaginator::class);
});
