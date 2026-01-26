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

it('returns Info DTO from info method', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $info = $this->sdk->info()->info();

    expect($info)->toBeInstanceOf(Info::class);
});

it('contains expected values from fixture', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $info = $this->sdk->info()->info();

    expect($info->version)->toBe('99.0.0.0.1.1')
        ->and($info->country)->toBe('Australia')
        ->and($info->maintenancePlanner)->toBeTrue()
        ->and($info->multiCompany)->toBeFalse();
});
