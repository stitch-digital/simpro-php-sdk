<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobDetail;
use Simpro\PhpSdk\Simpro\Requests\ContractorJobs\ListDetailedContractorJobsRequest;

it('sends list detailed contractor jobs request to correct endpoint', function () {
    MockClient::global([
        ListDetailedContractorJobsRequest::class => MockResponse::fixture('list_detailed_contractor_jobs_request'),
    ]);

    $request = new ListDetailedContractorJobsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list detailed contractor jobs response correctly', function () {
    MockClient::global([
        ListDetailedContractorJobsRequest::class => MockResponse::fixture('list_detailed_contractor_jobs_request'),
    ]);

    $request = new ListDetailedContractorJobsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(ContractorJobDetail::class)
        ->and($dto[0]->id)->toBe(1630)
        ->and($dto[0]->projectType)->toBe('Jobs')
        ->and($dto[0]->status)->toBe('Pending')
        ->and($dto[0]->total->exTax)->toBe(77.38)
        ->and($dto[0]->currency)->toBe('GBP');
});
