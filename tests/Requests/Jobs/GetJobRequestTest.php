<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\Job;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobStatus;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobTotal;
use Simpro\PhpSdk\Simpro\Requests\Jobs\GetJobRequest;

it('sends get job request to correct endpoint', function () {
    MockClient::global([
        GetJobRequest::class => MockResponse::fixture('get_job_request'),
    ]);

    $request = new GetJobRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get job response correctly', function () {
    MockClient::global([
        GetJobRequest::class => MockResponse::fixture('get_job_request'),
    ]);

    $request = new GetJobRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Job::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->type)->toBe('Project')
        ->and($dto->name)->toBe('Kitchen Renovation')
        ->and($dto->status)->toBeInstanceOf(JobStatus::class)
        ->and($dto->status->name)->toBe('Job : In Progress')
        ->and($dto->site)->not->toBeNull()
        ->and($dto->site->id)->toBe(10)
        ->and($dto->customer)->not->toBeNull()
        ->and($dto->customer->companyName)->toBe('Acme Corp')
        ->and($dto->total)->toBeInstanceOf(JobTotal::class)
        ->and($dto->total->incTax)->toBe(15000.00)
        ->and($dto->totals)->not->toBeNull()
        ->and($dto->totals->materialsCost)->not->toBeNull()
        ->and($dto->totals->materialsCost->actual)->toBe(5000.0)
        ->and($dto->sections)->not->toBeNull()
        ->and($dto->sections)->toHaveCount(1)
        ->and($dto->sections[0]->costCenters)->not->toBeNull()
        ->and($dto->customFields)->not->toBeNull()
        ->and($dto->customFields[0]->name)->toBe('Project Code')
        ->and($dto->customFields[0]->value)->toBe('PRJ-2024-001')
        ->and($dto->stc)->not->toBeNull()
        ->and($dto->stc->stcsEligible)->toBe(false);
});
