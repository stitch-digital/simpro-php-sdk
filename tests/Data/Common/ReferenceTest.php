<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

it('creates reference from array', function () {
    $ref = Reference::fromArray([
        'ID' => 123,
        'Name' => 'Test Reference',
    ]);

    expect($ref->id)->toBe(123)
        ->and($ref->name)->toBe('Test Reference');
});

it('handles missing name', function () {
    $ref = Reference::fromArray(['ID' => 456]);

    expect($ref->id)->toBe(456)
        ->and($ref->name)->toBeNull();
});

it('creates reference from id only', function () {
    $ref = Reference::fromId(789);

    expect($ref->id)->toBe(789)
        ->and($ref->name)->toBeNull();
});
