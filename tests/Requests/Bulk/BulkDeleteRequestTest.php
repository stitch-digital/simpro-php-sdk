<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;

it('resolves endpoint with /delete/ suffix', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs', []);
    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/jobs/delete/');
});

it('resolves endpoint with trailing slash', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs/', []);
    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/jobs/delete/');
});

it('sends bulk delete request and returns array of strings', function () {
    MockClient::global([
        BulkDeleteRequest::class => MockResponse::fixture('bulk_delete_request'),
    ]);

    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs', [210787, 210788, 210789]);

    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBe('2 section(s) deleted.')
        ->and($dto[1])->toBe('1 section(s) not found in project.');
});

it('wraps ids in IDs key in request body', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs', [210787, 210788]);
    $body = $request->body()->all();
    expect($body)->toBe(['IDs' => [210787, 210788]]);
});

it('uses POST method', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs', []);
    expect($request->getMethod()->value)->toBe('POST');
});

it('accepts string ids for attachments', function () {
    $request = new BulkDeleteRequest('/api/v1.0/companies/0/jobs/123/attachment/files', [
        'pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8',
        'MEPQuk-TKvn-IB9UWKI07tEGIH7lC2r91f7a5jSi5Ik',
    ]);

    $body = $request->body()->all();

    expect($body['IDs'])->toHaveCount(2)
        ->and($body['IDs'][0])->toBe('pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8');
});
