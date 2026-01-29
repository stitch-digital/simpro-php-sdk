<?php

declare(strict_types=1);

use Saloon\RateLimitPlugin\Stores\MemoryStore;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitBehavior;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

test('it has default values', function () {
    $config = new RateLimitConfig;

    expect($config->store)->toBeNull();
    expect($config->behavior)->toBe(RateLimitBehavior::Sleep);
    expect($config->requestsPerSecond)->toBe(10);
});

test('it has correct default constant', function () {
    expect(RateLimitConfig::DEFAULT_REQUESTS_PER_SECOND)->toBe(10);
});

test('it can be created with custom store', function () {
    $store = new MemoryStore;
    $config = new RateLimitConfig(store: $store);

    expect($config->store)->toBe($store);
});

test('it can be created with throw behavior', function () {
    $config = new RateLimitConfig(behavior: RateLimitBehavior::Throw);

    expect($config->behavior)->toBe(RateLimitBehavior::Throw);
});

test('it can be created with custom requests per second', function () {
    $config = new RateLimitConfig(requestsPerSecond: 5);

    expect($config->requestsPerSecond)->toBe(5);
});

test('default factory creates config with default values', function () {
    $config = RateLimitConfig::default();

    expect($config->store)->toBeNull();
    expect($config->behavior)->toBe(RateLimitBehavior::Sleep);
    expect($config->requestsPerSecond)->toBe(10);
});

test('withStore factory creates config with specified store', function () {
    $store = new MemoryStore;
    $config = RateLimitConfig::withStore($store);

    expect($config->store)->toBe($store);
    expect($config->behavior)->toBe(RateLimitBehavior::Sleep);
});

test('throwing factory creates config with throw behavior', function () {
    $config = RateLimitConfig::throwing();

    expect($config->behavior)->toBe(RateLimitBehavior::Throw);
    expect($config->store)->toBeNull();
});

test('getStore returns configured store', function () {
    $store = new MemoryStore;
    $config = new RateLimitConfig(store: $store);

    expect($config->getStore())->toBe($store);
});

test('getStore returns MemoryStore when store is null', function () {
    $config = new RateLimitConfig;

    expect($config->getStore())->toBeInstanceOf(MemoryStore::class);
});

test('shouldSleep returns true for sleep behavior', function () {
    $config = new RateLimitConfig(behavior: RateLimitBehavior::Sleep);

    expect($config->shouldSleep())->toBeTrue();
});

test('shouldSleep returns false for throw behavior', function () {
    $config = new RateLimitConfig(behavior: RateLimitBehavior::Throw);

    expect($config->shouldSleep())->toBeFalse();
});

test('it can be fully configured', function () {
    $store = new MemoryStore;
    $config = new RateLimitConfig(
        store: $store,
        behavior: RateLimitBehavior::Throw,
        requestsPerSecond: 5,
    );

    expect($config->store)->toBe($store);
    expect($config->behavior)->toBe(RateLimitBehavior::Throw);
    expect($config->requestsPerSecond)->toBe(5);
    expect($config->shouldSleep())->toBeFalse();
    expect($config->getStore())->toBe($store);
});
