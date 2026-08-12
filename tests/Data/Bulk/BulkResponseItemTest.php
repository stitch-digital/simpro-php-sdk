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

/**
 * @param  mixed  $body  The body exactly as Simpro nests it in the envelope.
 */
function failedItem(mixed $body, int $status = 422): BulkResponseItem
{
    return BulkResponseItem::fromArray([
        'status' => $status,
        'headers' => ['Batch-ID' => 414802],
        'body' => $body,
    ]);
}

it('decodes errors from the JSON string body Simpro sends', function () {
    $item = failedItem('{"errors":[{"path":"\/ProjectManager","message":"Must be an integer","value":{"ID":62}}]}');

    expect($item->errors())->toBe([
        ['path' => '/ProjectManager', 'message' => 'Must be an integer', 'value' => ['ID' => 62]],
    ])->and($item->errorMessage())->toBe('Must be an integer');
});

it('reads an already-decoded array body', function () {
    $item = failedItem(['errors' => [['path' => null, 'message' => 'Must be an integer', 'value' => null]]]);

    expect($item->errorMessage())->toBe('Must be an integer');
});

it('returns no errors when the body carries none', function () {
    expect(failedItem(null)->errors())->toBe([])
        ->and(failedItem(null)->errorMessage())->toBeNull()
        ->and(failedItem('not json')->errors())->toBe([])
        ->and(failedItem('{"errors":"nope"}')->errors())->toBe([])
        ->and(failedItem(['errors' => [['path' => '/Status']]])->errors())->toBe([]);
});

it('identifies a locked record', function () {
    $item = failedItem('{"errors":[{"path":null,"message":"This job is currently locked by Lauren Slender. Please try again later.","value":null}]}');

    expect($item->isLocked())->toBeTrue();
});

it('does not treat a validation failure or a non-422 as a lock', function () {
    expect(failedItem('{"errors":[{"path":"\/Status","message":"Must be an integer","value":{"ID":11}}]}')->isLocked())->toBeFalse()
        ->and(failedItem('{"errors":[{"message":"The job is locked."}]}', status: 500)->isLocked())->toBeFalse()
        ->and(failedItem(null)->isLocked())->toBeFalse();
});
