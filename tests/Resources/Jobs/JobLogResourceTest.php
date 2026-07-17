<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\Logs\JobLog;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Logs\ListJobLogsRequest;
use Simpro\PhpSdk\Simpro\Resources\Jobs\JobLogResource;

it('exposes jobLogs() on the connector returning the resource', function () {
    expect($this->sdk->jobLogs(companyId: 0))->toBeInstanceOf(JobLogResource::class);
});

it('list() returns a QueryBuilder that resolves to JobLog DTOs', function () {
    MockClient::global([
        ListJobLogsRequest::class => MockResponse::fixture('list_job_logs_request'),
    ]);

    $query = $this->sdk->jobLogs(companyId: 0)->list();

    expect($query)->toBeInstanceOf(QueryBuilder::class);

    $items = $query->all();

    expect($items)->toHaveCount(2)
        ->and($items[0])->toBeInstanceOf(JobLog::class)
        ->and($items[0]->id)->toBe(3609009);
});
