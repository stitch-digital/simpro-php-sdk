<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\BusinessGroup;
use Simpro\PhpSdk\Simpro\Data\Setup\BusinessGroupListItem;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\CreateBusinessGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\DeleteBusinessGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\GetBusinessGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\ListBusinessGroupsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups\UpdateBusinessGroupRequest;

it('sends list business groups request to correct endpoint', function () {
    MockClient::global([
        ListBusinessGroupsRequest::class => MockResponse::fixture('list_business_groups_request'),
    ]);

    $request = new ListBusinessGroupsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list business groups response correctly', function () {
    MockClient::global([
        ListBusinessGroupsRequest::class => MockResponse::fixture('list_business_groups_request'),
    ]);

    $request = new ListBusinessGroupsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(BusinessGroupListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Plumbing')
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Electrical');
});

it('sends get business group request to correct endpoint', function () {
    MockClient::global([
        GetBusinessGroupRequest::class => MockResponse::fixture('get_business_group_request'),
    ]);

    $request = new GetBusinessGroupRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get business group response correctly', function () {
    MockClient::global([
        GetBusinessGroupRequest::class => MockResponse::fixture('get_business_group_request'),
    ]);

    $request = new GetBusinessGroupRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(BusinessGroup::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Plumbing')
        ->and($dto->costCenters)->toBeArray()
        ->and($dto->costCenters)->toHaveCount(1);
});

it('sends create business group request and returns id', function () {
    MockClient::global([
        CreateBusinessGroupRequest::class => MockResponse::fixture('create_business_group_request'),
    ]);

    $request = new CreateBusinessGroupRequest(0, [
        'Name' => 'HVAC',
    ]);
    $response = $this->sdk->send($request);
    $id = $response->dto();

    expect($response->status())->toBe(201)
        ->and($id)->toBe(3);
});

it('sends update business group request', function () {
    MockClient::global([
        UpdateBusinessGroupRequest::class => MockResponse::fixture('update_business_group_request'),
    ]);

    $request = new UpdateBusinessGroupRequest(0, 1, [
        'Name' => 'Updated Plumbing',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('sends delete business group request', function () {
    MockClient::global([
        DeleteBusinessGroupRequest::class => MockResponse::fixture('delete_business_group_request'),
    ]);

    $request = new DeleteBusinessGroupRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('can access business groups via setup resource', function () {
    MockClient::global([
        ListBusinessGroupsRequest::class => MockResponse::fixture('list_business_groups_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->businessGroups()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get business group via setup resource', function () {
    MockClient::global([
        GetBusinessGroupRequest::class => MockResponse::fixture('get_business_group_request'),
    ]);

    $group = $this->sdk->setup(0)->businessGroups()->get(1);

    expect($group)->toBeInstanceOf(BusinessGroup::class)
        ->and($group->id)->toBe(1);
});

it('can create business group via setup resource', function () {
    MockClient::global([
        CreateBusinessGroupRequest::class => MockResponse::fixture('create_business_group_request'),
    ]);

    $id = $this->sdk->setup(0)->businessGroups()->create([
        'Name' => 'HVAC',
    ]);

    expect($id)->toBe(3);
});

it('can update business group via setup resource', function () {
    MockClient::global([
        UpdateBusinessGroupRequest::class => MockResponse::fixture('update_business_group_request'),
    ]);

    $response = $this->sdk->setup(0)->businessGroups()->update(1, [
        'Name' => 'Updated Plumbing',
    ]);

    expect($response->status())->toBe(204);
});

it('can delete business group via setup resource', function () {
    MockClient::global([
        DeleteBusinessGroupRequest::class => MockResponse::fixture('delete_business_group_request'),
    ]);

    $response = $this->sdk->setup(0)->businessGroups()->delete(1);

    expect($response->status())->toBe(204);
});
