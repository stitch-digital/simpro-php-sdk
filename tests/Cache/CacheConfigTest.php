<?php

declare(strict_types=1);

use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\FlysystemDriver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Drivers\PsrCacheDriver;
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;

test('it has correct default expiry constant', function () {
    expect(CacheConfig::DEFAULT_EXPIRY_SECONDS)->toBe(300);
});

test('it can be created with a driver', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);

    expect($config->driver)->toBe($driver);
    expect($config->expiryInSeconds)->toBe(300);
    expect($config->keyPrefix)->toBeNull();
});

test('it can be created with custom expiry', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver, expiryInSeconds: 600);

    expect($config->expiryInSeconds)->toBe(600);
});

test('it can be created with custom key prefix', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver, keyPrefix: 'custom-prefix');

    expect($config->keyPrefix)->toBe('custom-prefix');
});

test('psr16 factory creates config with PsrCacheDriver', function () {
    $cache = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $config = CacheConfig::psr16($cache);

    expect($config->driver)->toBeInstanceOf(PsrCacheDriver::class);
    expect($config->expiryInSeconds)->toBe(300);
    expect($config->keyPrefix)->toBeNull();
});

test('psr16 factory accepts custom expiry', function () {
    $cache = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $config = CacheConfig::psr16($cache, expiryInSeconds: 600);

    expect($config->expiryInSeconds)->toBe(600);
});

test('psr16 factory accepts custom key prefix', function () {
    $cache = Mockery::mock(\Psr\SimpleCache\CacheInterface::class);
    $config = CacheConfig::psr16($cache, keyPrefix: 'my-prefix');

    expect($config->keyPrefix)->toBe('my-prefix');
});

test('laravel factory creates config with LaravelCacheDriver', function () {
    $cache = Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);
    $config = CacheConfig::laravel($cache);

    expect($config->driver)->toBeInstanceOf(LaravelCacheDriver::class);
    expect($config->expiryInSeconds)->toBe(300);
    expect($config->keyPrefix)->toBeNull();
});

test('laravel factory accepts custom expiry', function () {
    $cache = Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);
    $config = CacheConfig::laravel($cache, expiryInSeconds: 900);

    expect($config->expiryInSeconds)->toBe(900);
});

test('laravel factory accepts custom key prefix', function () {
    $cache = Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);
    $config = CacheConfig::laravel($cache, keyPrefix: 'laravel-prefix');

    expect($config->keyPrefix)->toBe('laravel-prefix');
});

test('flysystem factory creates config with FlysystemDriver', function () {
    $filesystem = Mockery::mock(\League\Flysystem\Filesystem::class);
    $config = CacheConfig::flysystem($filesystem);

    expect($config->driver)->toBeInstanceOf(FlysystemDriver::class);
    expect($config->expiryInSeconds)->toBe(300);
    expect($config->keyPrefix)->toBeNull();
});

test('flysystem factory accepts custom expiry', function () {
    $filesystem = Mockery::mock(\League\Flysystem\Filesystem::class);
    $config = CacheConfig::flysystem($filesystem, expiryInSeconds: 1200);

    expect($config->expiryInSeconds)->toBe(1200);
});

test('flysystem factory accepts custom key prefix', function () {
    $filesystem = Mockery::mock(\League\Flysystem\Filesystem::class);
    $config = CacheConfig::flysystem($filesystem, keyPrefix: 'fs-prefix');

    expect($config->keyPrefix)->toBe('fs-prefix');
});

test('getDriver returns the configured driver', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);

    expect($config->getDriver())->toBe($driver);
});

test('getKeyPrefix returns the configured prefix', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver, keyPrefix: 'my-prefix');

    expect($config->getKeyPrefix())->toBe('my-prefix');
});

test('getKeyPrefix returns null when no prefix configured', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(driver: $driver);

    expect($config->getKeyPrefix())->toBeNull();
});

test('it can be fully configured', function () {
    $driver = Mockery::mock(Driver::class);
    $config = new CacheConfig(
        driver: $driver,
        expiryInSeconds: 600,
        keyPrefix: 'custom',
    );

    expect($config->driver)->toBe($driver);
    expect($config->expiryInSeconds)->toBe(600);
    expect($config->keyPrefix)->toBe('custom');
    expect($config->getDriver())->toBe($driver);
    expect($config->getKeyPrefix())->toBe('custom');
});
