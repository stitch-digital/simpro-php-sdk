<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Info\Info;
use Simpro\PhpSdk\Simpro\Requests\Info\InfoRequest;
use Simpro\PhpSdk\Simpro\Resources\InfoResource;

it('returns InfoResource from connector', function () {
    $resource = $this->sdk->info();

    expect($resource)->toBeInstanceOf(InfoResource::class);
});

it('returns Info DTO from get method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $info = $this->sdk->info()->get();

    expect($info)->toBeInstanceOf(Info::class);
});

it('contains expected values from fixture', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $info = $this->sdk->info()->get();

    expect($info->version)->toBe('99.0.0.0.1.1')
        ->and($info->country)->toBe('Australia')
        ->and($info->maintenancePlanner)->toBeTrue()
        ->and($info->multiCompany)->toBeFalse();
});

it('returns version via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $version = $this->sdk->info()->version();

    expect($version)->toBe('99.0.0.0.1.1');
});

it('returns country via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $country = $this->sdk->info()->country();

    expect($country)->toBe('Australia');
});

it('returns maintenancePlanner via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $maintenancePlanner = $this->sdk->info()->maintenancePlanner();

    expect($maintenancePlanner)->toBeTrue();
});

it('returns multiCompany via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $multiCompany = $this->sdk->info()->multiCompany();

    expect($multiCompany)->toBeFalse();
});

it('returns sharedCatalog via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $sharedCatalog = $this->sdk->info()->sharedCatalog();

    expect($sharedCatalog)->toBeFalse();
});

it('returns sharedStock via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $sharedStock = $this->sdk->info()->sharedStock();

    expect($sharedStock)->toBeFalse();
});

it('returns sharedClients via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $sharedClients = $this->sdk->info()->sharedClients();

    expect($sharedClients)->toBeFalse();
});

it('returns sharedSetup via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $sharedSetup = $this->sdk->info()->sharedSetup();

    expect($sharedSetup)->toBeFalse();
});

it('returns sharedDefaults via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $sharedDefaults = $this->sdk->info()->sharedDefaults();

    expect($sharedDefaults)->toBeFalse();
});

it('returns sharedAccountsIntegration via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $sharedAccountsIntegration = $this->sdk->info()->sharedAccountsIntegration();

    expect($sharedAccountsIntegration)->toBeFalse();
});

it('returns sharedVoip via convenience method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $sharedVoip = $this->sdk->info()->sharedVoip();

    expect($sharedVoip)->toBeFalse();
});
