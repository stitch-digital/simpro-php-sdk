<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;

it('resolves endpoint with /multiple/ suffix', function () {
    $request = new BulkCreateRequest('/api/v1.0/companies/0/contractors', []);
    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/contractors/multiple/');
});

it('resolves endpoint with trailing slash', function () {
    $request = new BulkCreateRequest('/api/v1.0/companies/0/contractors/', []);
    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/contractors/multiple/');
});

it('sends bulk create request and returns BulkResponse', function () {
    MockClient::global([
        BulkCreateRequest::class => MockResponse::fixture('bulk_create_request'),
    ]);

    $request = new BulkCreateRequest('/api/v1.0/companies/0/contractors', [
        ['GivenName' => 'Peter', 'FamilyName' => 'Smith'],
        ['GivenName' => 'Michael', 'FamilyName' => 'Dickson'],
    ]);

    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(BulkResponse::class)
        ->and($dto->items)->toHaveCount(2)
        ->and($dto->resourceIds())->toBe([1882, 1883])
        ->and($dto->allSuccessful())->toBeTrue();
});

it('uses POST method', function () {
    $request = new BulkCreateRequest('/api/v1.0/companies/0/contractors', []);
    expect($request->getMethod()->value)->toBe('POST');
});
