<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Employees\Timesheets\Timesheet;
use Simpro\PhpSdk\Simpro\Requests\Employees\Timesheets\ListTimesheetsRequest;

it('sends list employee timesheets request to correct endpoint', function () {
    MockClient::global([
        ListTimesheetsRequest::class => MockResponse::fixture('list_employee_timesheets_request'),
    ]);

    $request = new ListTimesheetsRequest(0, 123);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list employee timesheets response correctly', function () {
    MockClient::global([
        ListTimesheetsRequest::class => MockResponse::fixture('list_employee_timesheets_request'),
    ]);

    $request = new ListTimesheetsRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(Timesheet::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->startTime)->toBe('08:00')
        ->and($dto[0]->finishTime)->toBe('16:00')
        ->and($dto[0]->totalHours)->toBe(8.0)
        ->and($dto[0]->job->id)->toBe(100)
        ->and($dto[0]->billable)->toBeTrue()
        ->and($dto[0]->approved)->toBeTrue()
        ->and($dto[1])->toBeInstanceOf(Timesheet::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->approved)->toBeFalse();
});
