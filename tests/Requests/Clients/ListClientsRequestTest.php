<?php

declare(strict_types=1);

use Saloon\Exceptions\Request\ClientException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Auth\GetAccessTokenRequest;
use Simpro\PhpSdk\Simpro\Requests\Clients\ListClientsRequest;

it('lists clients successfully', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $request = new ListClientsRequest;
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Simpro\PhpSdk\Simpro\Data\Clients\ClientListResponse::class)
        ->and($dto->clients)->toHaveCount(2)
        ->and($dto->clients[0]->id)->toBe('1')
        ->and($dto->clients[0]->attributes->name)->toBe('Test Client 1')
        ->and($dto->links)->not->toBeNull()
        ->and($dto->meta)->not->toBeNull();
});

it('includes pagination query parameters', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $request = new ListClientsRequest(page: 2, perPage: 25);
    $response = $this->sdk->send($request);

    expect($request->query()->get('page[limit]'))->toBe(25)
        ->and($request->query()->get('page[offset]'))->toBe(25);
});

it('handles client list errors', function () {
    MockClient::destroyGlobal();
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::make(
            body: ['error' => 'Unauthorized'],
            status: 401
        ),
    ]);

    expect(fn () => $this->sdk->send(new ListClientsRequest))
        ->toThrow(ClientException::class);
});
