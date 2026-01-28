<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Common\TaxCode;

it('creates tax code from array', function () {
    $taxCode = TaxCode::fromArray([
        'ID' => 1,
        'Code' => 'GST',
        'Type' => 'Single',
        'Rate' => 10.0,
    ]);

    expect($taxCode->id)->toBe(1)
        ->and($taxCode->code)->toBe('GST')
        ->and($taxCode->type)->toBe('Single')
        ->and($taxCode->rate)->toBe(10.0);
});

it('handles missing optional fields', function () {
    $taxCode = TaxCode::fromArray(['ID' => 5]);

    expect($taxCode->id)->toBe(5)
        ->and($taxCode->code)->toBeNull()
        ->and($taxCode->type)->toBeNull()
        ->and($taxCode->rate)->toBeNull();
});

it('checks if single tax type', function () {
    $single = TaxCode::fromArray(['ID' => 1, 'Type' => 'Single']);
    $compound = TaxCode::fromArray(['ID' => 2, 'Type' => 'Compound']);

    expect($single->isSingle())->toBeTrue()
        ->and($compound->isSingle())->toBeFalse();
});

it('checks if compound tax type', function () {
    $compound = TaxCode::fromArray(['ID' => 1, 'Type' => 'Compound']);
    $single = TaxCode::fromArray(['ID' => 2, 'Type' => 'Single']);

    expect($compound->isCompound())->toBeTrue()
        ->and($single->isCompound())->toBeFalse();
});
