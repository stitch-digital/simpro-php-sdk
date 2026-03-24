<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponseItem;

it('creates from array with all fields', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 201,
        'headers' => [
            'Batch-ID' => 0,
            'Resource-ID' => 1882,
            'Location' => '/api/v1.0/companies/0/customers/individuals/1882',
        ],
        'body' => null,
    ]);

    expect($item)->toBeInstanceOf(BulkResponseItem::class)
        ->and($item->status)->toBe(201)
        ->and($item->batchId)->toBe(0)
        ->and($item->resourceId)->toBe(1882)
        ->and($item->location)->toBe('/api/v1.0/companies/0/customers/individuals/1882')
        ->and($item->body)->toBeNull();
});

it('creates from array with string resource id', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 201,
        'headers' => [
            'Batch-ID' => 0,
            'Resource-ID' => 'pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8',
            'Location' => '/api/v1.0/companies/0/jobs/123/attachment/files/pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8',
        ],
        'body' => null,
    ]);

    expect($item->resourceId)->toBe('pEwTUfJ9jXxtD6b1BZU2IZDwHaGoJX4ZuOBmw_DCKy8');
});

it('creates from array without location header', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 204,
        'headers' => [
            'Batch-ID' => 1,
            'Resource-ID' => 1885,
        ],
        'body' => null,
    ]);

    expect($item->location)->toBeNull();
});

it('identifies successful responses', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 201,
        'headers' => ['Batch-ID' => 0, 'Resource-ID' => 1],
        'body' => null,
    ]);

    expect($item->isSuccessful())->toBeTrue();
});

it('identifies failed responses', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 422,
        'headers' => ['Batch-ID' => 0, 'Resource-ID' => 0],
        'body' => ['Message' => 'Validation failed'],
    ]);

    expect($item->isSuccessful())->toBeFalse()
        ->and($item->body)->toBe(['Message' => 'Validation failed']);
});

it('handles 204 as successful', function () {
    $item = BulkResponseItem::fromArray([
        'status' => 204,
        'headers' => ['Batch-ID' => 0, 'Resource-ID' => 1884],
        'body' => null,
    ]);

    expect($item->isSuccessful())->toBeTrue();
});
