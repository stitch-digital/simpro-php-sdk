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
        ->and($dto[0]->type)->toBe('Job')
        ->and($dto[0]->subject)->toBe('Kitchen Install Day 1')
        ->and($dto[1])->toBeInstanceOf(ScheduleListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->subject)->toBe('Kitchen Install Day 2');
});
