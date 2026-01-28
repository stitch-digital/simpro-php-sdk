<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobListItem;
use Simpro\PhpSdk\Simpro\Requests\Jobs\ListJobsRequest;

it('sends list jobs request to correct endpoint', function () {
    MockClient::global([
        ListJobsRequest::class => MockResponse::fixture('list_jobs_request'),
    ]);

    $request = new ListJobsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list jobs response correctly', function () {
    MockClient::global([
        ListJobsRequest::class => MockResponse::fixture('list_jobs_request'),
    ]);

    $request = new ListJobsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->description)->toBe('Kitchen renovation project')
        ->and($dto[0]->total->exTax)->toBe(15000.00)
        ->and($dto[0]->total->tax)->toBe(1500.00)
        ->and($dto[0]->total->incTax)->toBe(16500.00)
        ->and($dto[1])->toBeInstanceOf(JobListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->description)->toBe('Plumbing repair service call');
});
