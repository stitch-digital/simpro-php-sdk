<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Schedules\ScheduleListItem;
use Simpro\PhpSdk\Simpro\Requests\Schedules\ListSchedulesRequest;

it('sends list schedules request to correct endpoint', function () {
    MockClient::global([
        ListSchedulesRequest::class => MockResponse::fixture('list_schedules_request'),
    ]);

    $request = new ListSchedulesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list schedules response correctly', function () {
    MockClient::global([
        ListSchedulesRequest::class => MockResponse::fixture('list_schedules_request'),
    ]);

    $request = new ListSchedulesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ScheduleListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->type)->toBe('job')
        ->and($dto[0]->reference)->toBe('100-233')
        ->and($dto[0]->totalHours)->toBe(8.0)
        ->and($dto[0]->date)->toBe('2024-01-25')
        ->and($dto[1])->toBeInstanceOf(ScheduleListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->date)->toBe('2024-01-26');
});

it('parses schedule list staff correctly', function () {
    MockClient::global([
        ListSchedulesRequest::class => MockResponse::fixture('list_schedules_request'),
    ]);

    $request = new ListSchedulesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->staff)->not->toBeNull()
        ->and($dto[0]->staff->id)->toBe(3)
        ->and($dto[0]->staff->name)->toBe('John Smith')
        ->and($dto[0]->staff->type)->toBe('employee')
        ->and($dto[0]->staff->typeId)->toBe(3);
});

it('parses schedule list blocks correctly', function () {
    MockClient::global([
        ListSchedulesRequest::class => MockResponse::fixture('list_schedules_request'),
    ]);

    $request = new ListSchedulesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->blocks)->toBeArray()
        ->and($dto[0]->blocks)->toHaveCount(1)
        ->and($dto[0]->blocks[0]->hrs)->toBe(8.0)
        ->and($dto[0]->blocks[0]->startTime)->toBe('08:00')
        ->and($dto[0]->blocks[0]->endTime)->toBe('16:00')
        ->and($dto[0]->blocks[0]->scheduleRate)->not->toBeNull()
        ->and($dto[0]->blocks[0]->scheduleRate->id)->toBe(1)
        ->and($dto[0]->blocks[0]->scheduleRate->name)->toBe('Normal Time');
});
