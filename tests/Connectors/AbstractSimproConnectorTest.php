<?php

declare(strict_types=1);

use Saloon\Contracts\Authenticator;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;
use Simpro\PhpSdk\Simpro\Paginators\SimproPaginator;

// Create a concrete implementation for testing
final class TestConnector extends AbstractSimproConnector
{
    protected function defaultAuth(): Authenticator
    {
        return new \Saloon\Http\Auth\TokenAuthenticator('test-token');
    }
}

// Create a dummy request for testing
final class DummyRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/test';
    }
}

// Create a paginatable dummy request for testing pagination
final class PaginatableDummyRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/test';
    }
}

test('it can be instantiated with base URL and timeout', function () {
    $connector = new TestConnector('https://test.simpro.com', 30);

    expect($connector->resolveBaseUrl())->toBe('https://test.simpro.com')
        ->and($connector->getRequestTimeout())->toBe(30.0);
});

test('it uses default timeout when not specified', function () {
    $connector = new TestConnector('https://test.simpro.com');

    expect($connector->getRequestTimeout())->toBe(10.0);
});

test('it accepts JSON by default', function () {
    $connector = new TestConnector('https://test.simpro.com');

    $mockClient = new MockClient([
        MockResponse::make(['status' => 'ok'], 200),
    ]);

    $connector->withMockClient($mockClient);
    $response = $connector->send(new DummyRequest);

    expect($response->getPendingRequest()->headers()->get('Accept'))->toBe('application/json');
});

test('it creates a paginator for requests', function () {
    $connector = new TestConnector('https://test.simpro.com');
    $request = new PaginatableDummyRequest;

    $paginator = $connector->paginate($request);

    expect($paginator)->toBeInstanceOf(SimproPaginator::class);
});

test('it throws validation exception for 422 responses', function () {
    $connector = new TestConnector('https://test.simpro.com');

    $mockClient = new MockClient([
        MockResponse::make([
            'errors' => [
                'name' => ['The name field is required.'],
            ],
        ], 422),
    ]);

    $connector->withMockClient($mockClient);

    $connector->send(new DummyRequest);
})->throws(ValidationException::class);
