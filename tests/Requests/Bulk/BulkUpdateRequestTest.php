<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;

it('resolves endpoint with /multiple/ suffix', function () {
    $request = new BulkUpdateRequest('/api/v1.0/companies/0/contractors', []);
    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/contractors/multiple/');
});

it('resolves endpoint with trailing slash', function () {
    $request = new BulkUpdateRequest('/api/v1.0/companies/0/contractors/', []);
    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/contractors/multiple/');
});

it('sends bulk update request and returns BulkResponse', function () {
    MockClient::global([
        BulkUpdateRequest::class => MockResponse::fixture('bulk_update_request'),
    ]);

    $request = new BulkUpdateRequest('/api/v1.0/companies/0/contractors', [
        ['ID' => 1884, 'GivenName' => 'Pete'],
        ['ID' => 1885, 'GivenName' => 'Mike'],
    ]);

    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(BulkResponse::class)
        ->and($dto->items)->toHaveCount(2)
        ->and($dto->resourceIds())->toBe([1884, 1885])
        ->and($dto->allSuccessful())->toBeTrue();
});

it('uses PATCH method', function () {
    $request = new BulkUpdateRequest('/api/v1.0/companies/0/contractors', []);
    expect($request->getMethod()->value)->toBe('PATCH');
});
