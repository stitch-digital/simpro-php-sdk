<?php

declare(strict_types=1);

use Saloon\CachePlugin\Contracts\Driver;
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;
use Simpro\PhpSdk\Simpro\Connectors\SimproOAuthConnector;

test('caching is disabled by default', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    expect($connector->hasCaching())->toBeFalse();
    expect($connector->getCacheConfig())->toBeNull();
});

test('caching can be enabled via constructor', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);

    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        cacheConfig: $config,
    );

    expect($connector->hasCaching())->toBeTrue();
    expect($connector->getCacheConfig())->toBe($config);
});

test('caching can be enabled after construction', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    expect($connector->hasCaching())->toBeFalse();

    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);
    $connector->setCacheConfig($config);

    expect($connector->hasCaching())->toBeTrue();
    expect($connector->getCacheConfig())->toBe($config);
});

test('caching can be disabled by setting config to null', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);

    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        cacheConfig: $config,
    );

    expect($connector->hasCaching())->toBeTrue();

    $connector->setCacheConfig(null);

    expect($connector->hasCaching())->toBeFalse();
    expect($connector->getCacheConfig())->toBeNull();
});

test('cache key uses hostname-based prefix for isolation', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);

    $connector1 = new SimproApiKeyConnector(
        baseUrl: 'https://company1.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        cacheConfig: $config,
    );

    $connector2 = new SimproApiKeyConnector(
        baseUrl: 'https://company2.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        cacheConfig: $config,
    );

    $reflection1 = new ReflectionClass($connector1);
    $method1 = $reflection1->getMethod('cacheKey');
    $method1->setAccessible(true);

    $reflection2 = new ReflectionClass($connector2);
    $method2 = $reflection2->getMethod('cacheKey');
    $method2->setAccessible(true);

    // Create a mock PendingRequest for testing
    $pendingRequest = Mockery::mock(\Saloon\Http\PendingRequest::class);
    $pendingRequest->shouldReceive('getUrl')->andReturn('https://example.simprosuite.com/api/v1.0/companies');
    $pendingRequest->shouldReceive('headers->all')->andReturn(['Accept' => 'application/json']);
    $pendingRequest->shouldReceive('query->all')->andReturn([]);

    $key1 = $method1->invoke($connector1, $pendingRequest);
    $key2 = $method2->invoke($connector2, $pendingRequest);

    expect($key1)->toStartWith('simpro:company1.simprosuite.com:');
    expect($key2)->toStartWith('simpro:company2.simprosuite.com:');
    expect($key1)->not->toBe($key2);
});

test('cache key includes custom prefix when configured', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver, keyPrefix: 'my-app');

    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        cacheConfig: $config,
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('cacheKey');
    $method->setAccessible(true);

    $pendingRequest = Mockery::mock(\Saloon\Http\PendingRequest::class);
    $pendingRequest->shouldReceive('getUrl')->andReturn('https://example.simprosuite.com/api/v1.0/companies');
    $pendingRequest->shouldReceive('headers->all')->andReturn(['Accept' => 'application/json']);
    $pendingRequest->shouldReceive('query->all')->andReturn([]);

    $key = $method->invoke($connector, $pendingRequest);

    expect($key)->toStartWith('simpro:example.simprosuite.com:my-app:');
});

test('resolveCacheDriver throws when caching not configured', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('resolveCacheDriver');
    $method->setAccessible(true);

    $method->invoke($connector);
})->throws(RuntimeException::class, 'Caching is not configured');

test('resolveCacheDriver returns configured driver', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);

    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        cacheConfig: $config,
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('resolveCacheDriver');
    $method->setAccessible(true);

    $resolvedDriver = $method->invoke($connector);

    expect($resolvedDriver)->toBe($driver);
});

test('cacheExpiryInSeconds returns configured expiry', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver, expiryInSeconds: 600);

    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        cacheConfig: $config,
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('cacheExpiryInSeconds');
    $method->setAccessible(true);

    $expiry = $method->invoke($connector);

    expect($expiry)->toBe(600);
});

test('cacheExpiryInSeconds returns default when not configured', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('cacheExpiryInSeconds');
    $method->setAccessible(true);

    $expiry = $method->invoke($connector);

    expect($expiry)->toBe(CacheConfig::DEFAULT_EXPIRY_SECONDS);
});

test('oauth connector caching is disabled by default', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://example.simprosuite.com',
        clientId: 'client-id',
        clientSecret: 'client-secret',
        redirectUri: 'https://app.example.com/callback',
    );

    expect($connector->hasCaching())->toBeFalse();
    expect($connector->getCacheConfig())->toBeNull();
});

test('oauth connector accepts cache config', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);

    $connector = new SimproOAuthConnector(
        baseUrl: 'https://example.simprosuite.com',
        clientId: 'client-id',
        clientSecret: 'client-secret',
        redirectUri: 'https://app.example.com/callback',
        cacheConfig: $config,
    );

    expect($connector->hasCaching())->toBeTrue();
    expect($connector->getCacheConfig())->toBe($config);
});

test('cache key returns null when caching disabled', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('cacheKey');
    $method->setAccessible(true);

    $pendingRequest = Mockery::mock(\Saloon\Http\PendingRequest::class);

    $key = $method->invoke($connector, $pendingRequest);

    expect($key)->toBeNull();
});

test('cache key handles base URL without host', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);

    $connector = new SimproApiKeyConnector(
        baseUrl: '/api/v1.0',
        apiKey: 'test-api-key',
        cacheConfig: $config,
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('cacheKey');
    $method->setAccessible(true);

    $pendingRequest = Mockery::mock(\Saloon\Http\PendingRequest::class);
    $pendingRequest->shouldReceive('getUrl')->andReturn('/api/v1.0/companies');
    $pendingRequest->shouldReceive('headers->all')->andReturn(['Accept' => 'application/json']);
    $pendingRequest->shouldReceive('query->all')->andReturn([]);

    $key = $method->invoke($connector, $pendingRequest);

    expect($key)->toStartWith('simpro:');
    expect($key)->not->toContain('simpro:simpro:');
});
