<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Setup\SecurityGroup;
use Simpro\PhpSdk\Simpro\Data\Setup\SecurityGroupListItem;
use Simpro\PhpSdk\Simpro\Requests\Setup\SecurityGroups\GetSecurityGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\SecurityGroups\ListDetailedSecurityGroupsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\SecurityGroups\ListSecurityGroupsRequest;

it('sends list security groups request to correct endpoint', function () {
    MockClient::global([
        ListSecurityGroupsRequest::class => MockResponse::fixture('list_security_groups_request'),
    ]);

    $request = new ListSecurityGroupsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list security groups response correctly', function () {
    MockClient::global([
        ListSecurityGroupsRequest::class => MockResponse::fixture('list_security_groups_request'),
    ]);

    $request = new ListSecurityGroupsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(SecurityGroupListItem::class)
        ->and($dto[0]->iD)->toBe(1)
        ->and($dto[0]->name)->toBe('Administrators')
        ->and($dto[1]->iD)->toBe(2)
        ->and($dto[1]->name)->toBe('Field Technicians');
});

it('sends get security group request to correct endpoint', function () {
    MockClient::global([
        GetSecurityGroupRequest::class => MockResponse::fixture('get_security_group_request'),
    ]);

    $request = new GetSecurityGroupRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get security group response correctly', function () {
    MockClient::global([
        GetSecurityGroupRequest::class => MockResponse::fixture('get_security_group_request'),
    ]);

    $request = new GetSecurityGroupRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(SecurityGroup::class)
        ->and($dto->iD)->toBe(1)
        ->and($dto->name)->toBe('Administrators')
        ->and($dto->dashboards)->toBeArray()
        ->and($dto->dashboards)->toHaveCount(2)
        ->and($dto->dashboards[0])->toBeInstanceOf(Reference::class)
        ->and($dto->dashboards[0]->id)->toBe(1)
        ->and($dto->dashboards[0]->name)->toBe('Main Dashboard')
        ->and($dto->businessGroup)->toBeInstanceOf(Reference::class)
        ->and($dto->businessGroup->id)->toBe(1)
        ->and($dto->businessGroup->name)->toBe('Default');
});

it('sends list detailed security groups request to correct endpoint', function () {
    MockClient::global([
        ListDetailedSecurityGroupsRequest::class => MockResponse::fixture('list_detailed_security_groups_request'),
    ]);

    $request = new ListDetailedSecurityGroupsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list detailed security groups response correctly', function () {
    MockClient::global([
        ListDetailedSecurityGroupsRequest::class => MockResponse::fixture('list_detailed_security_groups_request'),
    ]);

    $request = new ListDetailedSecurityGroupsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(SecurityGroup::class)
        ->and($dto[0]->iD)->toBe(1)
        ->and($dto[0]->name)->toBe('Administrators')
        ->and($dto[0]->dashboards)->toHaveCount(1)
        ->and($dto[0]->businessGroup)->toBeInstanceOf(Reference::class)
        ->and($dto[1])->toBeInstanceOf(SecurityGroup::class)
        ->and($dto[1]->iD)->toBe(2)
        ->and($dto[1]->name)->toBe('Field Technicians')
        ->and($dto[1]->dashboards)->toHaveCount(0)
        ->and($dto[1]->businessGroup)->toBeNull();
});

it('list detailed security groups request includes columns parameter', function () {
    MockClient::global([
        ListDetailedSecurityGroupsRequest::class => MockResponse::fixture('list_detailed_security_groups_request'),
    ]);

    $request = new ListDetailedSecurityGroupsRequest(0);

    expect($request->query()->all())->toHaveKey('columns')
        ->and($request->query()->get('columns'))->toBe('ID,Name,Dashboards,BusinessGroup');
});

it('can access security groups via setup resource', function () {
    MockClient::global([
        ListSecurityGroupsRequest::class => MockResponse::fixture('list_security_groups_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->securityGroups()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get security group via setup resource', function () {
    MockClient::global([
        GetSecurityGroupRequest::class => MockResponse::fixture('get_security_group_request'),
    ]);

    $group = $this->sdk->setup(0)->securityGroups()->get(1);

    expect($group)->toBeInstanceOf(SecurityGroup::class)
        ->and($group->iD)->toBe(1);
});

it('can list detailed security groups via setup resource', function () {
    MockClient::global([
        ListDetailedSecurityGroupsRequest::class => MockResponse::fixture('list_detailed_security_groups_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->securityGroups()->listDetailed();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});
