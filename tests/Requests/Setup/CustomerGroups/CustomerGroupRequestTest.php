<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerGroup;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerGroupListItem;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups\GetCustomerGroupRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups\ListCustomerGroupsRequest;

it('sends list customer groups request to correct endpoint', function () {
    MockClient::global([
        ListCustomerGroupsRequest::class => MockResponse::fixture('list_customer_groups_request'),
    ]);

    $request = new ListCustomerGroupsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list customer groups response correctly', function () {
    MockClient::global([
        ListCustomerGroupsRequest::class => MockResponse::fixture('list_customer_groups_request'),
    ]);

    $request = new ListCustomerGroupsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerGroupListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('VIP Customers')
        ->and($dto[1]->name)->toBe('Standard Customers');
});

it('parses get customer group response correctly', function () {
    MockClient::global([
        GetCustomerGroupRequest::class => MockResponse::fixture('get_customer_group_request'),
    ]);

    $request = new GetCustomerGroupRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(CustomerGroup::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('VIP Customers')
        ->and($dto->archived)->toBeFalse();
});

it('can access customer groups via setup resource', function () {
    MockClient::global([
        ListCustomerGroupsRequest::class => MockResponse::fixture('list_customer_groups_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->customerGroups()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get customer group via setup resource', function () {
    MockClient::global([
        GetCustomerGroupRequest::class => MockResponse::fixture('get_customer_group_request'),
    ]);

    $customerGroup = $this->sdk->setup(0)->customerGroups()->get(1);

    expect($customerGroup)->toBeInstanceOf(CustomerGroup::class)
        ->and($customerGroup->id)->toBe(1);
});
