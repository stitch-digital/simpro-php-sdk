<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Reports\JobCostToCompleteFinancial;
use Simpro\PhpSdk\Simpro\Requests\Reports\GetJobCostToCompleteFinancialRequest;

it('sends job cost to complete financial request to correct endpoint', function () {
    MockClient::global([
        GetJobCostToCompleteFinancialRequest::class => MockResponse::fixture('get_job_cost_to_complete_financial_request'),
    ]);

    $request = new GetJobCostToCompleteFinancialRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses job cost to complete financial response correctly', function () {
    MockClient::global([
        GetJobCostToCompleteFinancialRequest::class => MockResponse::fixture('get_job_cost_to_complete_financial_request'),
    ]);

    $request = new GetJobCostToCompleteFinancialRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobCostToCompleteFinancial::class)
        ->and($dto[0]->job->id)->toBe(12345)
        ->and($dto[0]->customer->id)->toBe(67890)
        ->and($dto[0]->site->id)->toBe(11111)
        ->and($dto[0]->requestNumber)->toBe('REQ-001')
        ->and($dto[0]->total)->toBe(50000.00)
        ->and($dto[0]->claimedToDate)->toBe(25000.00)
        ->and($dto[0]->billedPercentage)->toBe(50.00)
        ->and($dto[0]->costToDate)->toBe(20000.00)
        ->and($dto[0]->costToComplete)->toBe(15000.00)
        ->and($dto[0]->percentageComplete)->toBe(57.14)
        ->and($dto[0]->netMarginToDate)->toBe(5000.00)
        ->and($dto[0]->projectedNetMargin)->toBe(15000.00)
        ->and($dto[1])->toBeInstanceOf(JobCostToCompleteFinancial::class)
        ->and($dto[1]->job->id)->toBe(12346);
});

it('can be accessed via reports resource', function () {
    MockClient::global([
        GetJobCostToCompleteFinancialRequest::class => MockResponse::fixture('get_job_cost_to_complete_financial_request'),
    ]);

    $dto = $this->sdk->reports(0)->jobCostToCompleteFinancial();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobCostToCompleteFinancial::class);
});

it('supports filter parameters', function () {
    MockClient::global([
        GetJobCostToCompleteFinancialRequest::class => MockResponse::fixture('get_job_cost_to_complete_financial_request'),
    ]);

    $dto = $this->sdk->reports(0)->jobCostToCompleteFinancial([
        'date' => '2024-01-01',
        'changeOrders' => 'true',
        'businessGroup' => [1, 2, 3],
    ]);

    expect($dto)->toBeArray();
});
