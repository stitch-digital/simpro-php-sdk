<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\ActivitySchedules\ActivitySchedule;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\GetActivityScheduleRequest;

it('sends get activity schedule request to correct endpoint', function () {
    MockClient::global([
        GetActivityScheduleRequest::class => MockResponse::fixture('get_activity_schedule_request'),
    ]);

    $request = new GetActivityScheduleRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get activity schedule response correctly', function () {
    MockClient::global([
        GetActivityScheduleRequest::class => MockResponse::fixture('get_activity_schedule_request'),
    ]);

    $request = new GetActivityScheduleRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(ActivitySchedule::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->totalHours)->toBe(8.0)
        ->and($dto->notes)->toBe('Weekly sync meeting')
        ->and($dto->isLocked)->toBeFalse()
        ->and($dto->recurringScheduleId)->toBeNull()
        ->and($dto->date)->toBe('2024-03-15')
        ->and($dto->staff->id)->toBe(5)
        ->and($dto->staff->name)->toBe('John Smith')
        ->and($dto->activity->id)->toBe(10)
        ->and($dto->activity->name)->toBe('Team meeting')
        ->and($dto->dateModified)->not->toBeNull();
});

it('parses activity schedule blocks correctly', function () {
    MockClient::global([
        GetActivityScheduleRequest::class => MockResponse::fixture('get_activity_schedule_request'),
    ]);

    $request = new GetActivityScheduleRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto->blocks)->toBeArray()
        ->and($dto->blocks)->toHaveCount(1)
        ->and($dto->blocks[0]->hrs)->toBe(8.0)
        ->and($dto->blocks[0]->startTime)->toBe('09:00')
        ->and($dto->blocks[0]->endTime)->toBe('17:00')
        ->and($dto->blocks[0]->scheduleRate->id)->toBe(1)
        ->and($dto->blocks[0]->scheduleRate->name)->toBe('Standard');
});
