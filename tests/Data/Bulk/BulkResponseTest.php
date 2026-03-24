<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponseItem;

it('wraps bulk response items', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 1882, 'Location' => '/api/v1.0/companies/0/contractors/1882'], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 1, 'Resource-ID' => 1883, 'Location' => '/api/v1.0/companies/0/contractors/1883'], 'body' => null]),
    ]);

    expect($response->items)->toHaveCount(2)
        ->and($response->items[0])->toBeInstanceOf(BulkResponseItem::class)
        ->and($response->items[0]->resourceId)->toBe(1882)
        ->and($response->items[1]->resourceId)->toBe(1883);
});

it('returns all resource ids', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 1, 'Resource-ID' => 101], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 2, 'Resource-ID' => 102], 'body' => null]),
    ]);

    expect($response->resourceIds())->toBe([100, 101, 102]);
});

it('reports all successful when all items succeed', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 204, 'headers' => ['Batch-ID' => 1, 'Resource-ID' => 101], 'body' => null]),
    ]);

    expect($response->allSuccessful())->toBeTrue();
});

it('reports not all successful when any item fails', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 422, 'headers' => ['Batch-ID' => 1, 'Resource-ID' => 0], 'body' => ['Message' => 'Error']]),
    ]);

    expect($response->allSuccessful())->toBeFalse();
});

it('returns only failed items', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
        BulkResponseItem::fromArray(['status' => 422, 'headers' => ['Batch-ID' => 1, 'Resource-ID' => 0], 'body' => ['Message' => 'Error']]),
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 2, 'Resource-ID' => 102], 'body' => null]),
    ]);

    $failures = $response->failures();

    expect($failures)->toHaveCount(1)
        ->and($failures[0]->batchId)->toBe(1)
        ->and($failures[0]->status)->toBe(422);
});

it('returns empty failures when all succeed', function () {
    $response = new BulkResponse([
        BulkResponseItem::fromArray(['status' => 201, 'headers' => ['Batch-ID' => 0, 'Resource-ID' => 100], 'body' => null]),
    ]);

    expect($response->failures())->toBeEmpty();
});
