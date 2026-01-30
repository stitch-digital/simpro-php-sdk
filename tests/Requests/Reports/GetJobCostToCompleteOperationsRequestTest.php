<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Reports\JobCostToCompleteOperations;
use Simpro\PhpSdk\Simpro\Requests\Reports\GetJobCostToCompleteOperationsRequest;

it('sends job cost to complete operations request to correct endpoint', function () {
    MockClient::global([
        GetJobCostToCompleteOperationsRequest::class => MockResponse::fixture('get_job_cost_to_complete_operations_request'),
    ]);

    $request = new GetJobCostToCompleteOperationsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses job cost to complete operations response correctly', function () {
    MockClient::global([
        GetJobCostToCompleteOperationsRequest::class => MockResponse::fixture('get_job_cost_to_complete_operations_request'),
    ]);

    $request = new GetJobCostToCompleteOperationsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(JobCostToCompleteOperations::class)
        ->and($dto[0]->job->id)->toBe(12345)
        ->and($dto[0]->customer->id)->toBe(67890)
        ->and($dto[0]->site->id)->toBe(11111)
        ->and($dto[0]->requestNumber)->toBe('REQ-001')
        ->and($dto[0]->originalEstimatedBudget->materials)->toBe(10000.00)
        ->and($dto[0]->originalEstimatedBudget->resources)->toBe(15000.00)
        ->and($dto[0]->originalEstimatedBudget->resourceHours)->toBe(100.00)
        ->and($dto[0]->revisedEstimatedBudget->materials)->toBe(12000.00)
        ->and($dto[0]->currentBudget->materials)->toBe(12000.00)
        ->and($dto[0]->actualToDate->materials)->toBe(8000.00)
        ->and($dto[0]->forecastRemaining->materials)->toBe(4000.00)
        ->and($dto[0]->variance->materials)->toBe(0.00)
        ->and($dto[0]->percentage->materials)->toBe(66.67);
});

it('can be accessed via reports resource', function () {
    MockClient::global([
        GetJobCostToCompleteOperationsRequest::class => MockResponse::fixture('get_job_cost_to_complete_operations_request'),
    ]);

    $dto = $this->sdk->reports(0)->jobCostToCompleteOperations();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(JobCostToCompleteOperations::class);
});

it('supports filter parameters', function () {
    MockClient::global([
        GetJobCostToCompleteOperationsRequest::class => MockResponse::fixture('get_job_cost_to_complete_operations_request'),
    ]);

    $dto = $this->sdk->reports(0)->jobCostToCompleteOperations([
        'date' => '2024-01-01',
        'includeCommitted' => 'true',
        'costCentre' => [1, 2],
    ]);

    expect($dto)->toBeArray();
});
