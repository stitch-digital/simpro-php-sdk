<?php

declare(strict_types=1);

use Saloon\RateLimitPlugin\Stores\MemoryStore;
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;
use Simpro\PhpSdk\Simpro\Connectors\SimproOAuthConnector;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitBehavior;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

test('connector has rate limiting enabled by default', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    // Rate limiting is enabled by default (rateLimitingEnabled property is true)
    $reflection = new ReflectionClass($connector);
    $property = $reflection->getProperty('rateLimitingEnabled');
    $property->setAccessible(true);

    expect($property->getValue($connector))->toBeTrue();
});

test('connector can disable rate limiting', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    $connector->useRateLimitPlugin(false);

    $reflection = new ReflectionClass($connector);
    $property = $reflection->getProperty('rateLimitingEnabled');
    $property->setAccessible(true);

    expect($property->getValue($connector))->toBeFalse();
});

test('connector uses default rate limit config when not specified', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    $config = $connector->getRateLimitConfig();

    expect($config)->toBeInstanceOf(RateLimitConfig::class);
    expect($config->requestsPerSecond)->toBe(10);
    expect($config->behavior)->toBe(RateLimitBehavior::Sleep);
});

test('connector accepts custom rate limit config', function () {
    $customConfig = new RateLimitConfig(
        store: new MemoryStore,
        behavior: RateLimitBehavior::Throw,
        requestsPerSecond: 5,
    );

    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        rateLimitConfig: $customConfig,
    );

    $config = $connector->getRateLimitConfig();

    expect($config)->toBe($customConfig);
    expect($config->requestsPerSecond)->toBe(5);
    expect($config->behavior)->toBe(RateLimitBehavior::Throw);
});

test('connector can update rate limit config after construction', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    $newConfig = RateLimitConfig::throwing();
    $connector->setRateLimitConfig($newConfig);

    expect($connector->getRateLimitConfig())->toBe($newConfig);
});

test('oauth connector has rate limiting enabled by default', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://example.simprosuite.com',
        clientId: 'client-id',
        clientSecret: 'client-secret',
        redirectUri: 'https://app.example.com/callback',
    );

    $reflection = new ReflectionClass($connector);
    $property = $reflection->getProperty('rateLimitingEnabled');
    $property->setAccessible(true);

    expect($property->getValue($connector))->toBeTrue();
});

test('oauth connector accepts custom rate limit config', function () {
    $customConfig = new RateLimitConfig(
        store: new MemoryStore,
        behavior: RateLimitBehavior::Throw,
        requestsPerSecond: 5,
    );

    $connector = new SimproOAuthConnector(
        baseUrl: 'https://example.simprosuite.com',
        clientId: 'client-id',
        clientSecret: 'client-secret',
        redirectUri: 'https://app.example.com/callback',
        rateLimitConfig: $customConfig,
    );

    $config = $connector->getRateLimitConfig();

    expect($config)->toBe($customConfig);
});

test('rate limit uses hostname-based prefix for isolation', function () {
    $connector1 = new SimproApiKeyConnector(
        baseUrl: 'https://company1.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    $connector2 = new SimproApiKeyConnector(
        baseUrl: 'https://company2.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    $reflection = new ReflectionClass($connector1);
    $method = $reflection->getMethod('getLimiterPrefix');
    $method->setAccessible(true);

    $prefix1 = $method->invoke($connector1);
    $prefix2 = $method->invoke($connector2);

    expect($prefix1)->toBe('simpro:company1.simprosuite.com');
    expect($prefix2)->toBe('simpro:company2.simprosuite.com');
    expect($prefix1)->not->toBe($prefix2);
});

test('rate limit prefix handles base URL without host', function () {
    // This shouldn't happen in practice, but test the fallback
    $connector = new SimproApiKeyConnector(
        baseUrl: '/api/v1.0',
        apiKey: 'test-api-key',
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('getLimiterPrefix');
    $method->setAccessible(true);

    $prefix = $method->invoke($connector);

    expect($prefix)->toBe('simpro');
});

test('resolveLimits returns configured rate limit', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        rateLimitConfig: new RateLimitConfig(requestsPerSecond: 5),
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('resolveLimits');
    $method->setAccessible(true);

    $limits = $method->invoke($connector);

    expect($limits)->toBeArray();
    expect($limits)->toHaveCount(1);
});

test('resolveRateLimitStore returns configured store', function () {
    $store = new MemoryStore;
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
        rateLimitConfig: new RateLimitConfig(store: $store),
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('resolveRateLimitStore');
    $method->setAccessible(true);

    $resolvedStore = $method->invoke($connector);

    expect($resolvedStore)->toBe($store);
});

test('resolveRateLimitStore returns MemoryStore by default', function () {
    $connector = new SimproApiKeyConnector(
        baseUrl: 'https://example.simprosuite.com/api/v1.0',
        apiKey: 'test-api-key',
    );

    $reflection = new ReflectionClass($connector);
    $method = $reflection->getMethod('resolveRateLimitStore');
    $method->setAccessible(true);

    $resolvedStore = $method->invoke($connector);

    expect($resolvedStore)->toBeInstanceOf(MemoryStore::class);
});
