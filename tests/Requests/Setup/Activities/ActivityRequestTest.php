<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\Activity;
use Simpro\PhpSdk\Simpro\Data\Setup\ActivityListItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\CreateActivityRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\DeleteActivityRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\GetActivityRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\ListActivitiesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\ListDetailedActivitiesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Activities\UpdateActivityRequest;

it('sends list activities request to correct endpoint', function () {
    MockClient::global([
        ListActivitiesRequest::class => MockResponse::fixture('list_activities_request'),
    ]);

    $request = new ListActivitiesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list activities response correctly', function () {
    MockClient::global([
        ListActivitiesRequest::class => MockResponse::fixture('list_activities_request'),
    ]);

    $request = new ListActivitiesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(3)
        ->and($dto[0])->toBeInstanceOf(ActivityListItem::class)
        ->and($dto[0]->iD)->toBe(1)
        ->and($dto[0]->name)->toBe('Service Call')
        ->and($dto[1]->iD)->toBe(2)
        ->and($dto[1]->name)->toBe('Installation')
        ->and($dto[2]->iD)->toBe(3)
        ->and($dto[2]->name)->toBe('Maintenance');
});

it('sends get activity request to correct endpoint', function () {
    MockClient::global([
        GetActivityRequest::class => MockResponse::fixture('get_activity_request'),
    ]);

    $request = new GetActivityRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get activity response correctly with all fields', function () {
    MockClient::global([
        GetActivityRequest::class => MockResponse::fixture('get_activity_request'),
    ]);

    $request = new GetActivityRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Activity::class)
        ->and($dto->iD)->toBe(1)
        ->and($dto->name)->toBe('Service Call')
        ->and($dto->billable)->toBe(true)
        ->and($dto->archived)->toBe(false)
        ->and($dto->scheduleRate)->not->toBeNull()
        ->and($dto->scheduleRate->id)->toBe(5)
        ->and($dto->scheduleRate->name)->toBe('Standard Rate');
});

it('sends list detailed activities request to correct endpoint', function () {
    MockClient::global([
        ListDetailedActivitiesRequest::class => MockResponse::fixture('list_detailed_activities_request'),
    ]);

    $request = new ListDetailedActivitiesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('list detailed activities request includes columns parameter', function () {
    MockClient::global([
        ListDetailedActivitiesRequest::class => MockResponse::fixture('list_detailed_activities_request'),
    ]);

    $request = new ListDetailedActivitiesRequest(0);

    expect($request->query()->all())->toHaveKey('columns')
        ->and($request->query()->get('columns'))->toBe('ID,Name,Billable,Archived,ScheduleRate');
});

it('parses list detailed activities response correctly', function () {
    MockClient::global([
        ListDetailedActivitiesRequest::class => MockResponse::fixture('list_detailed_activities_request'),
    ]);

    $request = new ListDetailedActivitiesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(3)
        ->and($dto[0])->toBeInstanceOf(Activity::class)
        ->and($dto[0]->iD)->toBe(1)
        ->and($dto[0]->name)->toBe('Service Call')
        ->and($dto[0]->billable)->toBe(true)
        ->and($dto[0]->archived)->toBe(false)
        ->and($dto[0]->scheduleRate)->not->toBeNull()
        ->and($dto[0]->scheduleRate->id)->toBe(5)
        ->and($dto[1])->toBeInstanceOf(Activity::class)
        ->and($dto[1]->iD)->toBe(2)
        ->and($dto[1]->scheduleRate)->toBeNull()
        ->and($dto[2])->toBeInstanceOf(Activity::class)
        ->and($dto[2]->iD)->toBe(3)
        ->and($dto[2]->billable)->toBe(false)
        ->and($dto[2]->archived)->toBe(true);
});

it('sends create activity request and returns ID', function () {
    MockClient::global([
        CreateActivityRequest::class => MockResponse::fixture('create_activity_request'),
    ]);

    $request = new CreateActivityRequest(0, [
        'Name' => 'New Activity',
        'Billable' => true,
    ]);
    $response = $this->sdk->send($request);
    $id = $response->dto();

    expect($response->status())->toBe(201)
        ->and($id)->toBe(4);
});

it('sends update activity request', function () {
    MockClient::global([
        UpdateActivityRequest::class => MockResponse::fixture('update_activity_request'),
    ]);

    $request = new UpdateActivityRequest(0, 1, [
        'Name' => 'Updated Activity',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('sends delete activity request', function () {
    MockClient::global([
        DeleteActivityRequest::class => MockResponse::fixture('delete_activity_request'),
    ]);

    $request = new DeleteActivityRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('can access activities via setup resource', function () {
    MockClient::global([
        ListActivitiesRequest::class => MockResponse::fixture('list_activities_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->activities()->list();

    expect($queryBuilder)->toBeInstanceOf(QueryBuilder::class);
});

it('can get activity via setup resource', function () {
    MockClient::global([
        GetActivityRequest::class => MockResponse::fixture('get_activity_request'),
    ]);

    $activity = $this->sdk->setup(0)->activities()->get(1);

    expect($activity)->toBeInstanceOf(Activity::class)
        ->and($activity->iD)->toBe(1);
});

it('can list detailed activities via setup resource', function () {
    MockClient::global([
        ListDetailedActivitiesRequest::class => MockResponse::fixture('list_detailed_activities_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->activities()->listDetailed();

    expect($queryBuilder)->toBeInstanceOf(QueryBuilder::class);
});

it('can create activity via setup resource', function () {
    MockClient::global([
        CreateActivityRequest::class => MockResponse::fixture('create_activity_request'),
    ]);

    $id = $this->sdk->setup(0)->activities()->create([
        'Name' => 'New Activity',
    ]);

    expect($id)->toBe(4);
});

it('can update activity via setup resource', function () {
    MockClient::global([
        UpdateActivityRequest::class => MockResponse::fixture('update_activity_request'),
    ]);

    $response = $this->sdk->setup(0)->activities()->update(1, [
        'Name' => 'Updated Activity',
    ]);

    expect($response->status())->toBe(204);
});

it('can delete activity via setup resource', function () {
    MockClient::global([
        DeleteActivityRequest::class => MockResponse::fixture('delete_activity_request'),
    ]);

    $response = $this->sdk->setup(0)->activities()->delete(1);

    expect($response->status())->toBe(204);
});
