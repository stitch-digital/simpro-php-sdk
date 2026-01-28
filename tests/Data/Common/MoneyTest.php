<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Common\Money;
use Simpro\PhpSdk\Simpro\Data\Common\TaxCode;

it('creates money from array', function () {
    $money = Money::fromArray([
        'ExTax' => 100.00,
        'Tax' => 10.00,
        'IncTax' => 110.00,
    ]);

    expect($money->exTax)->toBe(100.0)
        ->and($money->tax)->toBe(10.0)
        ->and($money->incTax)->toBe(110.0);
});

it('handles missing values with defaults', function () {
    $money = Money::fromArray([]);

    expect($money->exTax)->toBe(0.0)
        ->and($money->tax)->toBe(0.0)
        ->and($money->incTax)->toBe(0.0);
});

it('creates money from float', function () {
    $money = Money::fromFloat(150.00);

    expect($money->exTax)->toBe(150.0)
        ->and($money->tax)->toBe(0.0)
        ->and($money->incTax)->toBe(150.0);
});

it('checks if money is zero', function () {
    $zero = Money::fromArray(['ExTax' => 0, 'Tax' => 0, 'IncTax' => 0]);
    $nonZero = Money::fromArray(['ExTax' => 100, 'Tax' => 10, 'IncTax' => 110]);

    expect($zero->isZero())->toBeTrue()
        ->and($nonZero->isZero())->toBeFalse();
});

it('calculates tax rate', function () {
    $money = Money::fromArray([
        'ExTax' => 100.00,
        'Tax' => 10.00,
        'IncTax' => 110.00,
    ]);

    expect($money->taxRate())->toBe(10.0);
});

it('returns zero tax rate when ex-tax is zero', function () {
    $money = Money::fromArray(['ExTax' => 0, 'Tax' => 0, 'IncTax' => 0]);

    expect($money->taxRate())->toBe(0.0);
});

it('includes tax code when present', function () {
    $money = Money::fromArray([
        'ExTax' => 100.00,
        'Tax' => 10.00,
        'IncTax' => 110.00,
        'TaxCode' => [
            'ID' => 1,
            'Code' => 'GST',
            'Type' => 'Single',
            'Rate' => 10.0,
        ],
    ]);

    expect($money->hasTaxCode())->toBeTrue()
        ->and($money->taxCode)->toBeInstanceOf(TaxCode::class)
        ->and($money->taxCode->code)->toBe('GST')
        ->and($money->taxCode->rate)->toBe(10.0);
});

it('handles missing tax code', function () {
    $money = Money::fromArray([
        'ExTax' => 100.00,
        'Tax' => 10.00,
        'IncTax' => 110.00,
    ]);

    expect($money->hasTaxCode())->toBeFalse()
        ->and($money->taxCode)->toBeNull();
});
