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
        ->and($dto->type)->toBe('job')
        ->and($dto->reference)->toBe('100-233')
        ->and($dto->totalHours)->toBe(8.0)
        ->and($dto->notes)->toBe('Bring all tools and materials')
        ->and($dto->date)->toBe('2024-01-25')
        ->and($dto->href)->toBe('/api/v1.0/companies/0/schedules/1')
        ->and($dto->dateModified)->not->toBeNull();
});

it('parses schedule staff correctly', function () {
    MockClient::global([
        GetScheduleRequest::class => MockResponse::fixture('get_schedule_request'),
    ]);

    $request = new GetScheduleRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->staff)->not->toBeNull()
        ->and($dto->staff->id)->toBe(3)
        ->and($dto->staff->name)->toBe('John Smith')
        ->and($dto->staff->type)->toBe('employee')
        ->and($dto->staff->typeId)->toBe(3);
});

it('parses schedule blocks correctly', function () {
    MockClient::global([
        GetScheduleRequest::class => MockResponse::fixture('get_schedule_request'),
    ]);

    $request = new GetScheduleRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->blocks)->toBeArray()
        ->and($dto->blocks)->toHaveCount(1)
        ->and($dto->blocks[0]->hrs)->toBe(8.0)
        ->and($dto->blocks[0]->startTime)->toBe('08:00')
        ->and($dto->blocks[0]->endTime)->toBe('16:00')
        ->and($dto->blocks[0]->iso8601StartTime)->not->toBeNull()
        ->and($dto->blocks[0]->scheduleRate)->not->toBeNull()
        ->and($dto->blocks[0]->scheduleRate->id)->toBe(1)
        ->and($dto->blocks[0]->scheduleRate->name)->toBe('Normal Time');
});
