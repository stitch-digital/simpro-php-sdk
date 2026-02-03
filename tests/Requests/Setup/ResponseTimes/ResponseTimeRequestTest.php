<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\ResponseTime;
use Simpro\PhpSdk\Simpro\Data\Setup\ResponseTimeListItem;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\ResponseTimes\CreateResponseTimeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ResponseTimes\DeleteResponseTimeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ResponseTimes\GetResponseTimeRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ResponseTimes\ListDetailedResponseTimesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ResponseTimes\ListResponseTimesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ResponseTimes\UpdateResponseTimeRequest;

it('sends list response times request to correct endpoint', function () {
    MockClient::global([
        ListResponseTimesRequest::class => MockResponse::fixture('list_response_times_request'),
    ]);

    $request = new ListResponseTimesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list response times response correctly', function () {
    MockClient::global([
        ListResponseTimesRequest::class => MockResponse::fixture('list_response_times_request'),
    ]);

    $request = new ListResponseTimesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(3)
        ->and($dto[0])->toBeInstanceOf(ResponseTimeListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('24 Hours Emergency Response')
        ->and($dto[0]->archived)->toBe(false)
        ->and($dto[2]->id)->toBe(3)
        ->and($dto[2]->name)->toBe('Legacy Response Time')
        ->and($dto[2]->archived)->toBe(true);
});

it('sends get response time request to correct endpoint', function () {
    MockClient::global([
        GetResponseTimeRequest::class => MockResponse::fixture('get_response_time_request'),
    ]);

    $request = new GetResponseTimeRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get response time response correctly with all fields', function () {
    MockClient::global([
        GetResponseTimeRequest::class => MockResponse::fixture('get_response_time_request'),
    ]);

    $request = new GetResponseTimeRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(ResponseTime::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('24 Hours Emergency Response')
        ->and($dto->days)->toBe(0)
        ->and($dto->hours)->toBe(24)
        ->and($dto->minutes)->toBe(0)
        ->and($dto->includeWeekends)->toBe(true)
        ->and($dto->archived)->toBe(false);
});

it('sends list detailed response times request to correct endpoint', function () {
    MockClient::global([
        ListDetailedResponseTimesRequest::class => MockResponse::fixture('list_detailed_response_times_request'),
    ]);

    $request = new ListDetailedResponseTimesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('list detailed response times request includes columns parameter', function () {
    MockClient::global([
        ListDetailedResponseTimesRequest::class => MockResponse::fixture('list_detailed_response_times_request'),
    ]);

    $request = new ListDetailedResponseTimesRequest(0);

    expect($request->query()->all())->toHaveKey('columns')
        ->and($request->query()->get('columns'))->toBe('ID,Name,Days,Hours,Minutes,IncludeWeekends,Archived');
});

it('parses list detailed response times response correctly', function () {
    MockClient::global([
        ListDetailedResponseTimesRequest::class => MockResponse::fixture('list_detailed_response_times_request'),
    ]);

    $request = new ListDetailedResponseTimesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ResponseTime::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('24 Hours Emergency Response')
        ->and($dto[0]->days)->toBe(0)
        ->and($dto[0]->hours)->toBe(24)
        ->and($dto[0]->minutes)->toBe(0)
        ->and($dto[0]->includeWeekends)->toBe(true)
        ->and($dto[0]->archived)->toBe(false)
        ->and($dto[1])->toBeInstanceOf(ResponseTime::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->days)->toBe(2)
        ->and($dto[1]->hours)->toBe(0)
        ->and($dto[1]->includeWeekends)->toBe(false);
});

it('sends create response time request and returns ID', function () {
    MockClient::global([
        CreateResponseTimeRequest::class => MockResponse::fixture('create_response_time_request'),
    ]);

    $request = new CreateResponseTimeRequest(0, [
        'Name' => 'New Response Time',
        'Days' => 1,
        'Hours' => 0,
        'Minutes' => 0,
        'IncludeWeekends' => false,
    ]);
    $response = $this->sdk->send($request);
    $id = $response->dto();

    expect($response->status())->toBe(201)
        ->and($id)->toBe(4);
});

it('sends update response time request', function () {
    MockClient::global([
        UpdateResponseTimeRequest::class => MockResponse::fixture('update_response_time_request'),
    ]);

    $request = new UpdateResponseTimeRequest(0, 1, [
        'Name' => 'Updated Response Time',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('sends delete response time request', function () {
    MockClient::global([
        DeleteResponseTimeRequest::class => MockResponse::fixture('delete_response_time_request'),
    ]);

    $request = new DeleteResponseTimeRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('can access response times via setup resource', function () {
    MockClient::global([
        ListResponseTimesRequest::class => MockResponse::fixture('list_response_times_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->responseTimes()->list();

    expect($queryBuilder)->toBeInstanceOf(QueryBuilder::class);
});

it('can get response time via setup resource', function () {
    MockClient::global([
        GetResponseTimeRequest::class => MockResponse::fixture('get_response_time_request'),
    ]);

    $responseTime = $this->sdk->setup(0)->responseTimes()->get(1);

    expect($responseTime)->toBeInstanceOf(ResponseTime::class)
        ->and($responseTime->id)->toBe(1);
});

it('can list detailed response times via setup resource', function () {
    MockClient::global([
        ListDetailedResponseTimesRequest::class => MockResponse::fixture('list_detailed_response_times_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->responseTimes()->listDetailed();

    expect($queryBuilder)->toBeInstanceOf(QueryBuilder::class);
});

it('can create response time via setup resource', function () {
    MockClient::global([
        CreateResponseTimeRequest::class => MockResponse::fixture('create_response_time_request'),
    ]);

    $id = $this->sdk->setup(0)->responseTimes()->create([
        'Name' => 'New Response Time',
    ]);

    expect($id)->toBe(4);
});

it('can update response time via setup resource', function () {
    MockClient::global([
        UpdateResponseTimeRequest::class => MockResponse::fixture('update_response_time_request'),
    ]);

    $response = $this->sdk->setup(0)->responseTimes()->update(1, [
        'Name' => 'Updated Response Time',
    ]);

    expect($response->status())->toBe(204);
});

it('can delete response time via setup resource', function () {
    MockClient::global([
        DeleteResponseTimeRequest::class => MockResponse::fixture('delete_response_time_request'),
    ]);

    $response = $this->sdk->setup(0)->responseTimes()->delete(1);

    expect($response->status())->toBe(204);
});
