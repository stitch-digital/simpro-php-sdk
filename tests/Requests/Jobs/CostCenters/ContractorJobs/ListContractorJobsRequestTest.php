<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ContractorJobs\ContractorJobListItem;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\ListContractorJobsRequest;

it('sends list job contractor jobs request to correct endpoint', function () {
    MockClient::global([
        ListContractorJobsRequest::class => MockResponse::fixture('list_job_contractor_jobs_request'),
    ]);

    $request = new ListContractorJobsRequest(0, 414786, 15311, 15615);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list job contractor jobs response correctly', function () {
    MockClient::global([
        ListContractorJobsRequest::class => MockResponse::fixture('list_job_contractor_jobs_request'),
    ]);

    $request = new ListContractorJobsRequest(0, 414786, 15311, 15615);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ContractorJobListItem::class)
        ->and($dto[0]->id)->toBe(1630)
        ->and($dto[0]->projectType)->toBe('Jobs')
        ->and($dto[0]->contractor->id)->toBe(327)
        ->and($dto[0]->contractor->name)->toBe('Marcus Hughes')
        ->and($dto[0]->createdBy)->toBeNull()
        ->and($dto[0]->total->exTax)->toBe(77.38)
        ->and($dto[0]->total->incTax)->toBe(92.86)
        ->and($dto[1])->toBeInstanceOf(ContractorJobListItem::class)
        ->and($dto[1]->id)->toBe(1629);
});
