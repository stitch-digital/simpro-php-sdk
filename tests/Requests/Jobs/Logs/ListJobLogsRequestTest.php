<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\Logs\JobLog;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Logs\ListJobLogsRequest;

it('sends the request to the job logs endpoint', function () {
    MockClient::global([
        ListJobLogsRequest::class => MockResponse::fixture('list_job_logs_request'),
    ]);

    $request = new ListJobLogsRequest(0);

    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/logs/jobs/')
        ->and($this->sdk->send($request)->status())->toBe(200);
});

it('parses the response into JobLog DTOs', function () {
    MockClient::global([
        ListJobLogsRequest::class => MockResponse::fixture('list_job_logs_request'),
    ]);

    $dto = $this->sdk->send(new ListJobLogsRequest(0))->dto();

    expect($dto)->toBeArray()->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobLog::class)
        ->and($dto[0]->id)->toBe(3609009)
        ->and($dto[0]->jobId)->toBe(481833)
        ->and($dto[0]->staff->name)->toBe('Garry Phillips')
        ->and($dto[1]->id)->toBe(3609020);
});
