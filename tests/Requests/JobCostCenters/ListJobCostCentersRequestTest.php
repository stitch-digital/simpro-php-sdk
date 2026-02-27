<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\JobCostCenters\JobCostCenterJob;
use Simpro\PhpSdk\Simpro\Data\JobCostCenters\JobCostCenterListItem;
use Simpro\PhpSdk\Simpro\Requests\JobCostCenters\ListJobCostCentersRequest;

it('sends list job cost centers request to correct endpoint', function () {
    MockClient::global([
        ListJobCostCentersRequest::class => MockResponse::fixture('list_job_cost_centers_request'),
    ]);

    $request = new ListJobCostCentersRequest(companyId: 0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list job cost centers response correctly', function () {
    MockClient::global([
        ListJobCostCentersRequest::class => MockResponse::fixture('list_job_cost_centers_request'),
    ]);

    $request = new ListJobCostCentersRequest(companyId: 0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobCostCenterListItem::class)
        ->and($dto[0]->id)->toBe(101)
        ->and($dto[0]->costCenter)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->costCenter->id)->toBe(5)
        ->and($dto[0]->costCenter->name)->toBe('Labour')
        ->and($dto[0]->name)->toBe('Main Labour Cost Centre')
        ->and($dto[0]->job)->toBeInstanceOf(JobCostCenterJob::class)
        ->and($dto[0]->job->id)->toBe(1001)
        ->and($dto[0]->job->type)->toBe('Project')
        ->and($dto[0]->job->name)->toBe('Office Fitout')
        ->and($dto[0]->job->stage)->toBe('In Progress')
        ->and($dto[0]->job->status)->toBe('Active')
        ->and($dto[0]->section)->toBeInstanceOf(Reference::class)
        ->and($dto[0]->section->id)->toBe(10)
        ->and($dto[0]->section->name)->toBe('Section A')
        ->and($dto[0]->dateModified)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->href)->toBe('/api/v1.0/companies/0/jobs/1001/sections/10/costCenters/101')
        ->and($dto[1])->toBeInstanceOf(JobCostCenterListItem::class)
        ->and($dto[1]->id)->toBe(202)
        ->and($dto[1]->job->type)->toBe('Service');
});
