<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Clients\Sector;

it('has all expected sector values', function () {
    $expectedSectors = [
        'Accommodation and Food Services',
        'Agriculture, Forestry and Fishing',
        'Arts and Recreation Services',
        'Construction',
        'Education and Training',
        'Electricity, Gas, Water and Waste Services',
        'Financial and Insurance Services',
        'Fire and Safety Services',
        'Health Care and Social Assistance',
        'Information Media and Telecommunications',
        'Manufacturing',
        'Mining',
        'Professional, Scientific and Technical Services',
        'Public Administration',
        'Property Management (Residential)',
        'Property Management (Commercial)',
        'Retail Trade',
        'Real Estate Investment Trust',
        'Transport, Postal and Warehousing',
        'Wholesale Trade',
        'Other Services',
    ];

    $enumValues = array_map(fn (Sector $sector) => $sector->value, Sector::cases());

    expect($enumValues)->toHaveCount(21)
        ->and($enumValues)->toContain(...$expectedSectors);
});

it('can be created from string value', function () {
    $sector = Sector::tryFrom('Construction');

    expect($sector)->toBe(Sector::Construction)
        ->and($sector->value)->toBe('Construction');
});

it('returns null for invalid sector string', function () {
    $sector = Sector::tryFrom('Invalid Sector');

    expect($sector)->toBeNull();
});

it('has display names for sectors with special formatting', function () {
    expect(Sector::Construction->displayName())->toBe('Construction and Installation')
        ->and(Sector::PublicAdministration->displayName())->toBe('Public Administration (Local, State or Federal Government)')
        ->and(Sector::RealEstateInvestmentTrust->displayName())->toBe('Real Estate Investment Trust (REIT)')
        ->and(Sector::RetailTrade->displayName())->toBe('Retail Trade');
});

it('can use tryFromString helper method', function () {
    $sector = Sector::tryFromString('Construction');

    expect($sector)->toBe(Sector::Construction);
});
