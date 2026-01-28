<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Schedules\Schedule;
use Simpro\PhpSdk\Simpro\Requests\Schedules\GetScheduleRequest;

it('sends get schedule request to correct endpoint', function () {
    MockClient::global([
        GetScheduleRequest::class => MockResponse::fixture('get_schedule_request'),
    ]);

    $request = new GetScheduleRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get schedule response correctly', function () {
    MockClient::global([
        GetScheduleRequest::class => MockResponse::fixture('get_schedule_request'),
    ]);

    $request = new GetScheduleRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Schedule::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->type)->toBe('Job')
        ->and($dto->subject)->toBe('Kitchen Install Day 1')
        ->and($dto->startTime)->toBe('08:00')
        ->and($dto->endTime)->toBe('16:00')
        ->and($dto->staff)->not->toBeNull()
        ->and($dto->staff->name)->toBe('John Smith')
        ->and($dto->job)->not->toBeNull()
        ->and($dto->job->name)->toBe('Kitchen Renovation');
});
