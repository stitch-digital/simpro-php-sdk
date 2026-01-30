<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\AccountingCategory;
use Simpro\PhpSdk\Simpro\Data\Setup\AccountingCategoryListItem;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\CreateAccountingCategoryRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\DeleteAccountingCategoryRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\GetAccountingCategoryRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\ListAccountingCategoriesRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories\UpdateAccountingCategoryRequest;

it('sends list accounting categories request to correct endpoint', function () {
    MockClient::global([
        ListAccountingCategoriesRequest::class => MockResponse::fixture('list_accounting_categories_request'),
    ]);

    $request = new ListAccountingCategoriesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list accounting categories response correctly', function () {
    MockClient::global([
        ListAccountingCategoriesRequest::class => MockResponse::fixture('list_accounting_categories_request'),
    ]);

    $request = new ListAccountingCategoriesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(AccountingCategoryListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Materials')
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Labor');
});

it('sends get accounting category request to correct endpoint', function () {
    MockClient::global([
        GetAccountingCategoryRequest::class => MockResponse::fixture('get_accounting_category_request'),
    ]);

    $request = new GetAccountingCategoryRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get accounting category response correctly', function () {
    MockClient::global([
        GetAccountingCategoryRequest::class => MockResponse::fixture('get_accounting_category_request'),
    ]);

    $request = new GetAccountingCategoryRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(AccountingCategory::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Materials')
        ->and($dto->ref)->toBe('ACC-001')
        ->and($dto->archived)->toBeFalse();
});

it('sends create accounting category request and returns id', function () {
    MockClient::global([
        CreateAccountingCategoryRequest::class => MockResponse::fixture('create_accounting_category_request'),
    ]);

    $request = new CreateAccountingCategoryRequest(0, [
        'Name' => 'Equipment',
    ]);
    $response = $this->sdk->send($request);
    $id = $response->dto();

    expect($response->status())->toBe(201)
        ->and($id)->toBe(3);
});

it('sends update accounting category request', function () {
    MockClient::global([
        UpdateAccountingCategoryRequest::class => MockResponse::fixture('update_accounting_category_request'),
    ]);

    $request = new UpdateAccountingCategoryRequest(0, 1, [
        'Name' => 'Updated Materials',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('sends delete accounting category request', function () {
    MockClient::global([
        DeleteAccountingCategoryRequest::class => MockResponse::fixture('delete_accounting_category_request'),
    ]);

    $request = new DeleteAccountingCategoryRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('can access accounting categories via setup resource', function () {
    MockClient::global([
        ListAccountingCategoriesRequest::class => MockResponse::fixture('list_accounting_categories_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->accountingCategories()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get accounting category via setup resource', function () {
    MockClient::global([
        GetAccountingCategoryRequest::class => MockResponse::fixture('get_accounting_category_request'),
    ]);

    $category = $this->sdk->setup(0)->accountingCategories()->get(1);

    expect($category)->toBeInstanceOf(AccountingCategory::class)
        ->and($category->id)->toBe(1);
});

it('can create accounting category via setup resource', function () {
    MockClient::global([
        CreateAccountingCategoryRequest::class => MockResponse::fixture('create_accounting_category_request'),
    ]);

    $id = $this->sdk->setup(0)->accountingCategories()->create([
        'Name' => 'Equipment',
    ]);

    expect($id)->toBe(3);
});

it('can update accounting category via setup resource', function () {
    MockClient::global([
        UpdateAccountingCategoryRequest::class => MockResponse::fixture('update_accounting_category_request'),
    ]);

    $response = $this->sdk->setup(0)->accountingCategories()->update(1, [
        'Name' => 'Updated Materials',
    ]);

    expect($response->status())->toBe(204);
});

it('can delete accounting category via setup resource', function () {
    MockClient::global([
        DeleteAccountingCategoryRequest::class => MockResponse::fixture('delete_accounting_category_request'),
    ]);

    $response = $this->sdk->setup(0)->accountingCategories()->delete(1);

    expect($response->status())->toBe(204);
});
