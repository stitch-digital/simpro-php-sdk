<?php

declare(strict_types=1);

use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

// Create a dummy request for testing
final class DummyApiKeyRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/test';
    }
}

// Create a paginatable dummy request for testing pagination
final class PaginatableDummyApiKeyRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/test';
    }
}

test('it can be instantiated with API key', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://test.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key'
    );

    expect($connector->resolveBaseUrl())->toBe('https://test.simprosuite.com/api/v1.0');
});

test('it can be instantiated with custom timeout', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://test.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        requestTimeout: 30
    );

    expect($connector->getRequestTimeout())->toBe(30.0);
});

test('it uses default timeout when not specified', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://test.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key'
    );

    expect($connector->getRequestTimeout())->toBe(10.0);
});

test('it authenticates requests with API key', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://test.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key-12345'
    );

    $mockClient = new MockClient([
        MockResponse::make(['data' => []], 200),
    ]);

    $connector->withMockClient($mockClient);
    $response = $connector->send(new DummyApiKeyRequest);

    expect($response->getPendingRequest()->headers()->get('Authorization'))
        ->toBe('Bearer test-api-key-12345');
});

test('it sends requests with correct base URL', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://custom.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key'
    );

    $mockClient = new MockClient([
        MockResponse::make(['data' => []], 200),
    ]);

    $connector->withMockClient($mockClient);
    $response = $connector->send(new DummyApiKeyRequest);

    $url = $response->getPendingRequest()->getUrl();

    expect($url)->toContain('custom.simprosuite.com');
});

test('it creates paginator for requests', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://test.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key'
    );

    $paginator = $connector->paginate(new PaginatableDummyApiKeyRequest);

    expect($paginator)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Paginators\SimproPaginator::class);
});
