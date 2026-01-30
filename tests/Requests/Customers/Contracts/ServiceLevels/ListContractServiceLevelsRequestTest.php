<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ServiceLevel;
use Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\ServiceLevels\ListContractServiceLevelsRequest;

it('sends list contract service levels request to correct endpoint', function () {
    MockClient::global([
        ListContractServiceLevelsRequest::class => MockResponse::fixture('list_contract_service_levels_request'),
    ]);

    $request = new ListContractServiceLevelsRequest(0, 123, 100);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list contract service levels response correctly', function () {
    MockClient::global([
        ListContractServiceLevelsRequest::class => MockResponse::fixture('list_contract_service_levels_request'),
    ]);

    $request = new ListContractServiceLevelsRequest(0, 123, 100);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ServiceLevel::class)
        ->and($dto[0]->serviceLevel->id)->toBe(1)
        ->and($dto[0]->serviceLevel->name)->toBe('Priority Support')
        ->and($dto[0]->assetType->id)->toBe(1)
        ->and($dto[0]->assetType->name)->toBe('HVAC Unit')
        ->and($dto[1])->toBeInstanceOf(ServiceLevel::class)
        ->and($dto[1]->serviceLevel->id)->toBe(2)
        ->and($dto[1]->serviceLevel->name)->toBe('Standard Support')
        ->and($dto[1]->assetType->name)->toBe('Fire Extinguisher');
});
