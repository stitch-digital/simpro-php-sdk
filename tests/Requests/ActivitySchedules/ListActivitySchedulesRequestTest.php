<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\ActivitySchedules\ActivityScheduleListItem;
use Simpro\PhpSdk\Simpro\Requests\ActivitySchedules\ListActivitySchedulesRequest;

it('sends list activity schedules request to correct endpoint', function () {
    MockClient::global([
        ListActivitySchedulesRequest::class => MockResponse::fixture('list_activity_schedules_request'),
    ]);

    $request = new ListActivitySchedulesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list activity schedules response correctly', function () {
    MockClient::global([
        ListActivitySchedulesRequest::class => MockResponse::fixture('list_activity_schedules_request'),
    ]);

    $request = new ListActivitySchedulesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ActivityScheduleListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->totalHours)->toBe(8.0)
        ->and($dto[0]->date)->toBe('2024-03-15')
        ->and($dto[0]->staff->id)->toBe(5)
        ->and($dto[0]->staff->name)->toBe('John Smith')
        ->and($dto[0]->staff->type)->toBe('employee')
        ->and($dto[0]->activity->id)->toBe(10)
        ->and($dto[0]->activity->name)->toBe('Team meeting')
        ->and($dto[1])->toBeInstanceOf(ActivityScheduleListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->totalHours)->toBe(4.5)
        ->and($dto[1]->activity->name)->toBe('Site inspection');
});
