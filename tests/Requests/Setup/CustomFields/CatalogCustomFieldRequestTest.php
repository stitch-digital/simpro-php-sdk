<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomField;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomFieldListItem;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\CreateCatalogCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\DeleteCatalogCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\GetCatalogCustomFieldRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\ListCatalogCustomFieldsRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\CustomFields\Catalogs\UpdateCatalogCustomFieldRequest;

it('sends list catalog custom fields request to correct endpoint', function () {
    MockClient::global([
        ListCatalogCustomFieldsRequest::class => MockResponse::fixture('list_catalog_custom_fields_request'),
    ]);

    $request = new ListCatalogCustomFieldsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list catalog custom fields response correctly', function () {
    MockClient::global([
        ListCatalogCustomFieldsRequest::class => MockResponse::fixture('list_catalog_custom_fields_request'),
    ]);

    $request = new ListCatalogCustomFieldsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomFieldListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Serial Number')
        ->and($dto[0]->type)->toBe('Text')
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('Warranty Period');
});

it('sends get catalog custom field request to correct endpoint', function () {
    MockClient::global([
        GetCatalogCustomFieldRequest::class => MockResponse::fixture('get_catalog_custom_field_request'),
    ]);

    $request = new GetCatalogCustomFieldRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get catalog custom field response correctly', function () {
    MockClient::global([
        GetCatalogCustomFieldRequest::class => MockResponse::fixture('get_catalog_custom_field_request'),
    ]);

    $request = new GetCatalogCustomFieldRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(CustomField::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Serial Number')
        ->and($dto->type)->toBe('Text')
        ->and($dto->customFieldType)->toBe('Catalog')
        ->and($dto->required)->toBeFalse()
        ->and($dto->archived)->toBeFalse()
        ->and($dto->options)->toBeArray();
});

it('sends create catalog custom field request and returns id', function () {
    MockClient::global([
        CreateCatalogCustomFieldRequest::class => MockResponse::fixture('create_catalog_custom_field_request'),
    ]);

    $request = new CreateCatalogCustomFieldRequest(0, [
        'Name' => 'Model Number',
        'Type' => 'Text',
    ]);
    $response = $this->sdk->send($request);
    $id = $response->dto();

    expect($response->status())->toBe(201)
        ->and($id)->toBe(3);
});

it('sends update catalog custom field request', function () {
    MockClient::global([
        UpdateCatalogCustomFieldRequest::class => MockResponse::fixture('update_catalog_custom_field_request'),
    ]);

    $request = new UpdateCatalogCustomFieldRequest(0, 1, [
        'Name' => 'Updated Serial Number',
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('sends delete catalog custom field request', function () {
    MockClient::global([
        DeleteCatalogCustomFieldRequest::class => MockResponse::fixture('delete_catalog_custom_field_request'),
    ]);

    $request = new DeleteCatalogCustomFieldRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(204);
});

it('can access catalog custom fields via setup resource', function () {
    MockClient::global([
        ListCatalogCustomFieldsRequest::class => MockResponse::fixture('list_catalog_custom_fields_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->customFields()->catalogs()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get catalog custom field via setup resource', function () {
    MockClient::global([
        GetCatalogCustomFieldRequest::class => MockResponse::fixture('get_catalog_custom_field_request'),
    ]);

    $customField = $this->sdk->setup(0)->customFields()->catalogs()->get(1);

    expect($customField)->toBeInstanceOf(CustomField::class)
        ->and($customField->id)->toBe(1);
});

it('can create catalog custom field via setup resource', function () {
    MockClient::global([
        CreateCatalogCustomFieldRequest::class => MockResponse::fixture('create_catalog_custom_field_request'),
    ]);

    $id = $this->sdk->setup(0)->customFields()->catalogs()->create([
        'Name' => 'Model Number',
        'Type' => 'Text',
    ]);

    expect($id)->toBe(3);
});

it('can update catalog custom field via setup resource', function () {
    MockClient::global([
        UpdateCatalogCustomFieldRequest::class => MockResponse::fixture('update_catalog_custom_field_request'),
    ]);

    $response = $this->sdk->setup(0)->customFields()->catalogs()->update(1, [
        'Name' => 'Updated Serial Number',
    ]);

    expect($response->status())->toBe(204);
});

it('can delete catalog custom field via setup resource', function () {
    MockClient::global([
        DeleteCatalogCustomFieldRequest::class => MockResponse::fixture('delete_catalog_custom_field_request'),
    ]);

    $response = $this->sdk->setup(0)->customFields()->catalogs()->delete(1);

    expect($response->status())->toBe(204);
});
