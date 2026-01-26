<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Info\Info;
use Simpro\PhpSdk\Simpro\Requests\Info\InfoRequest;

it('sends info request to correct endpoint', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $request = new InfoRequest;
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('uses GET method', function () {
    $request = new InfoRequest;

    expect($request->getMethod()->value)->toBe('GET');
});

it('parses info response correctly', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $request = new InfoRequest;
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Info::class);
});

it('maps PascalCase to camelCase', function () {
    MockClient::global([
        InfoRequest::class => MockResponse::fixture('info_request'),
    ]);

    $request = new InfoRequest;
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->version)->toBe('99.0.0.0.1.1')
        ->and($dto->country)->toBe('Australia')
        ->and($dto->maintenancePlanner)->toBeTrue()
        ->and($dto->multiCompany)->toBeFalse()
        ->and($dto->sharedCatalog)->toBeFalse()
        ->and($dto->sharedStock)->toBeFalse()
        ->and($dto->sharedClients)->toBeFalse()
        ->and($dto->sharedSetup)->toBeFalse()
        ->and($dto->sharedDefaults)->toBeFalse()
        ->and($dto->sharedAccountsIntegration)->toBeFalse()
        ->and($dto->sharedVoip)->toBeFalse();
});
