<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskAssignee;
use Simpro\PhpSdk\Simpro\Data\Tasks\TaskListItem;
use Simpro\PhpSdk\Simpro\Requests\Tasks\ListTasksRequest;

it('sends list tasks request to correct endpoint', function () {
    MockClient::global([
        ListTasksRequest::class => MockResponse::fixture('list_tasks_request'),
    ]);

    $request = new ListTasksRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/tasks/');
});

it('parses list tasks response correctly', function () {
    MockClient::global([
        ListTasksRequest::class => MockResponse::fixture('list_tasks_request'),
    ]);

    $request = new ListTasksRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(TaskListItem::class)
        ->and($dto[0]->id)->toBe(79527)
        ->and($dto[0]->subject)->toBe('Please Order and Send Tom Burchall 23.03')
        ->and($dto[1])->toBeInstanceOf(TaskListItem::class)
        ->and($dto[1]->id)->toBe(79524)
        ->and($dto[1]->subject)->toBe('Jaz Quote');
});

it('parses assigned to correctly', function () {
    MockClient::global([
        ListTasksRequest::class => MockResponse::fixture('list_tasks_request'),
    ]);

    $request = new ListTasksRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->assignedTo)->toBeInstanceOf(TaskAssignee::class)
        ->and($dto[0]->assignedTo->id)->toBe(3578)
        ->and($dto[0]->assignedTo->name)->toBe('Stores')
        ->and($dto[0]->assignedTo->type)->toBe('employee')
        ->and($dto[0]->assignedTo->typeId)->toBe(3578);
});

it('parses assignees correctly', function () {
    MockClient::global([
        ListTasksRequest::class => MockResponse::fixture('list_tasks_request'),
    ]);

    $request = new ListTasksRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->assignees)->toBeArray()
        ->and($dto[0]->assignees)->toHaveCount(1)
        ->and($dto[0]->assignees[0])->toBeInstanceOf(TaskAssignee::class)
        ->and($dto[0]->assignees[0]->id)->toBe(3578)
        ->and($dto[0]->assignees[0]->name)->toBe('Stores');
});

it('parses null fields correctly', function () {
    MockClient::global([
        ListTasksRequest::class => MockResponse::fixture('list_tasks_request'),
    ]);

    $request = new ListTasksRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->completedBy)->toBeNull()
        ->and($dto[0]->dueDate)->toBeNull()
        ->and($dto[0]->percentComplete)->toBeNull();
});
