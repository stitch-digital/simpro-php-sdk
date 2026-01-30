<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Setup\CostCenterRates;
use Simpro\PhpSdk\Simpro\Data\Setup\SetupCostCenter;
use Simpro\PhpSdk\Simpro\Data\Setup\SetupCostCenterListItem;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\CreateSetupCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\DeleteSetupCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\GetSetupCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\ListSetupCostCentersRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters\UpdateSetupCostCenterRequest;

it('sends list cost centers request to correct endpoint', function () {
    MockClient::global([
        ListSetupCostCentersRequest::class => MockResponse::fixture('list_setup_cost_centers_request'),
    ]);

    $request = new ListSetupCostCentersRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list cost centers response correctly', function () {
    MockClient::global([
        ListSetupCostCentersRequest::class => MockResponse::fixture('list_setup_cost_centers_request'),
    ]);

    $request = new ListSetupCostCentersRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(SetupCostCenterListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Service')
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Projects');
});

it('sends get cost center request to correct endpoint', function () {
    MockClient::global([
        GetSetupCostCenterRequest::class => MockResponse::fixture('get_setup_cost_center_request'),
    ]);

    $request = new GetSetupCostCenterRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get cost center response correctly', function () {
    MockClient::global([
        GetSetupCostCenterRequest::class => MockResponse::fixture('get_setup_cost_center_request'),
    ]);

    $request = new GetSetupCostCenterRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(SetupCostCenter::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Service')
        ->and($dto->incomeAccountNo)->toBe('4000')
        ->and($dto->expenseAccountNo)->toBe('5000')
        ->and($dto->monthlySalesBudget)->toBe(50000.00)
        ->and($dto->monthlyExpenditureBudget)->toBe(25000.00)
        ->and($dto->archived)->toBeFalse()
        ->and($dto->isMembershipCostCenter)->toBeFalse()
        ->and($dto->rates)->toBeInstanceOf(CostCenterRates::class)
        ->and($dto->rates->laborRate)->toBeInstanceOf(Reference::class)
        ->and($dto->rates->laborRate->id)->toBe(1);
});

it('sends create cost center request and returns id', function () {
    MockClient::global([
        CreateSetupCostCenterRequest::class => MockResponse::fixture('create_setup_cost_center_request'),
    ]);

    $request = new CreateSetupCostCenterRequest(0, [
        'Name' => 'Maintenance',
    ]);
    $response = $this->sdk->send($request);
    $id = $response->dto();

    expect($response->status())->toBe(201)
        ->and($id)->toBe(3);
});

it('sends update cost center request', function () {
    MockClient::global([
        UpdateSetupCostCenterRequest::class => MockResponse::fixture('update_setup_cost_center_request'),
    ]);

    $request = new UpdateSetupCostCenterRequest(0, 1, [
        'Name' => 'Updated Service',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('sends delete cost center request', function () {
    MockClient::global([
        DeleteSetupCostCenterRequest::class => MockResponse::fixture('delete_setup_cost_center_request'),
    ]);

    $request = new DeleteSetupCostCenterRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('can access cost centers via setup resource', function () {
    MockClient::global([
        ListSetupCostCentersRequest::class => MockResponse::fixture('list_setup_cost_centers_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->costCenters()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get cost center via setup resource', function () {
    MockClient::global([
        GetSetupCostCenterRequest::class => MockResponse::fixture('get_setup_cost_center_request'),
    ]);

    $costCenter = $this->sdk->setup(0)->costCenters()->get(1);

    expect($costCenter)->toBeInstanceOf(SetupCostCenter::class)
        ->and($costCenter->id)->toBe(1);
});

it('can create cost center via setup resource', function () {
    MockClient::global([
        CreateSetupCostCenterRequest::class => MockResponse::fixture('create_setup_cost_center_request'),
    ]);

    $id = $this->sdk->setup(0)->costCenters()->create([
        'Name' => 'Maintenance',
    ]);

    expect($id)->toBe(3);
});

it('can update cost center via setup resource', function () {
    MockClient::global([
        UpdateSetupCostCenterRequest::class => MockResponse::fixture('update_setup_cost_center_request'),
    ]);

    $response = $this->sdk->setup(0)->costCenters()->update(1, [
        'Name' => 'Updated Service',
    ]);

    expect($response->status())->toBe(204);
});

it('can delete cost center via setup resource', function () {
    MockClient::global([
        DeleteSetupCostCenterRequest::class => MockResponse::fixture('delete_setup_cost_center_request'),
    ]);

    $response = $this->sdk->setup(0)->costCenters()->delete(1);

    expect($response->status())->toBe(204);
});
