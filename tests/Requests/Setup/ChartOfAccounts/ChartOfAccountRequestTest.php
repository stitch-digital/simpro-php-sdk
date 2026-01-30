<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\ChartOfAccount;
use Simpro\PhpSdk\Simpro\Data\Setup\ChartOfAccountListItem;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\CreateChartOfAccountRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\DeleteChartOfAccountRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\GetChartOfAccountRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\ListChartOfAccountsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\ChartOfAccounts\UpdateChartOfAccountRequest;

it('sends list chart of accounts request to correct endpoint', function () {
    MockClient::global([
        ListChartOfAccountsRequest::class => MockResponse::fixture('list_chart_of_accounts_request'),
    ]);

    $request = new ListChartOfAccountsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list chart of accounts response correctly', function () {
    MockClient::global([
        ListChartOfAccountsRequest::class => MockResponse::fixture('list_chart_of_accounts_request'),
    ]);

    $request = new ListChartOfAccountsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ChartOfAccountListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Cash')
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Accounts Receivable');
});

it('sends get chart of account request to correct endpoint', function () {
    MockClient::global([
        GetChartOfAccountRequest::class => MockResponse::fixture('get_chart_of_account_request'),
    ]);

    $request = new GetChartOfAccountRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get chart of account response correctly', function () {
    MockClient::global([
        GetChartOfAccountRequest::class => MockResponse::fixture('get_chart_of_account_request'),
    ]);

    $request = new GetChartOfAccountRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(ChartOfAccount::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Cash')
        ->and($dto->number)->toBe('1000')
        ->and($dto->type)->toBe('Asset')
        ->and($dto->archived)->toBeFalse();
});

it('sends create chart of account request and returns id', function () {
    MockClient::global([
        CreateChartOfAccountRequest::class => MockResponse::fixture('create_chart_of_account_request'),
    ]);

    $request = new CreateChartOfAccountRequest(0, [
        'Name' => 'Petty Cash',
        'AccountNo' => '1010',
    ]);
    $response = $this->sdk->send($request);
    $id = $response->dto();

    expect($response->status())->toBe(201)
        ->and($id)->toBe(3);
});

it('sends update chart of account request', function () {
    MockClient::global([
        UpdateChartOfAccountRequest::class => MockResponse::fixture('update_chart_of_account_request'),
    ]);

    $request = new UpdateChartOfAccountRequest(0, 1, [
        'Name' => 'Cash on Hand',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('sends delete chart of account request', function () {
    MockClient::global([
        DeleteChartOfAccountRequest::class => MockResponse::fixture('delete_chart_of_account_request'),
    ]);

    $request = new DeleteChartOfAccountRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('can access chart of accounts via setup resource', function () {
    MockClient::global([
        ListChartOfAccountsRequest::class => MockResponse::fixture('list_chart_of_accounts_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->chartOfAccounts()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get chart of account via setup resource', function () {
    MockClient::global([
        GetChartOfAccountRequest::class => MockResponse::fixture('get_chart_of_account_request'),
    ]);

    $account = $this->sdk->setup(0)->chartOfAccounts()->get(1);

    expect($account)->toBeInstanceOf(ChartOfAccount::class)
        ->and($account->id)->toBe(1);
});

it('can create chart of account via setup resource', function () {
    MockClient::global([
        CreateChartOfAccountRequest::class => MockResponse::fixture('create_chart_of_account_request'),
    ]);

    $id = $this->sdk->setup(0)->chartOfAccounts()->create([
        'Name' => 'Petty Cash',
        'AccountNo' => '1010',
    ]);

    expect($id)->toBe(3);
});

it('can update chart of account via setup resource', function () {
    MockClient::global([
        UpdateChartOfAccountRequest::class => MockResponse::fixture('update_chart_of_account_request'),
    ]);

    $response = $this->sdk->setup(0)->chartOfAccounts()->update(1, [
        'Name' => 'Cash on Hand',
    ]);

    expect($response->status())->toBe(204);
});

it('can delete chart of account via setup resource', function () {
    MockClient::global([
        DeleteChartOfAccountRequest::class => MockResponse::fixture('delete_chart_of_account_request'),
    ]);

    $response = $this->sdk->setup(0)->chartOfAccounts()->delete(1);

    expect($response->status())->toBe(204);
});
