<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobListItem;
use Simpro\PhpSdk\Simpro\Requests\ContractorJobs\ListContractorJobsRequest;

it('sends list contractor jobs request to correct endpoint', function () {
    MockClient::global([
        ListContractorJobsRequest::class => MockResponse::fixture('list_contractor_jobs_request'),
    ]);

    $request = new ListContractorJobsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list contractor jobs response correctly', function () {
    MockClient::global([
        ListContractorJobsRequest::class => MockResponse::fixture('list_contractor_jobs_request'),
    ]);

    $request = new ListContractorJobsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ContractorJobListItem::class)
        ->and($dto[0]->id)->toBe(1630)
        ->and($dto[0]->projectType)->toBe('Jobs')
        ->and($dto[0]->contractor->id)->toBe(327)
        ->and($dto[0]->contractor->name)->toBe('Marcus Hughes')
        ->and($dto[0]->contractor->contactName)->toBe('Marcus Hughes')
        ->and($dto[0]->createdBy)->toBeNull()
        ->and($dto[0]->total->exTax)->toBe(77.38)
        ->and($dto[0]->total->incTax)->toBe(92.86)
        ->and($dto[0]->total->reverseChargeTax)->toBe(0.0)
        ->and($dto[0]->href)->toBe('/api/v1.0/companies/0/jobs/414786/sections/15311/costCenters/15615/contractorJobs/1630')
        ->and($dto[1])->toBeInstanceOf(ContractorJobListItem::class)
        ->and($dto[1]->id)->toBe(1629);
});
