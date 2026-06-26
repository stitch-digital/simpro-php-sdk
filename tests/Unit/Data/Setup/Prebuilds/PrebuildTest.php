<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Setup\Prebuilds\Prebuild;

it('hydrates from the Simpro list payload', function (): void {
    $prebuild = Prebuild::fromArray([
        'ID' => 18368,
        'Group' => [
            'ID' => 1235,
            'Name' => 'Places for People',
            'ParentGroup' => [
                'ID' => 36,
                'Name' => 'Maintenance & Monitoring Contracts',
            ],
        ],
        'PartNo' => '',
        'Name' => 'PFP - Roller Shutter Maintenance',
        'SalesTaxCode' => [
            'ID' => 3,
            'Code' => 'VAT',
            'Rate' => 20,
        ],
        'Materials' => 0,
        'Labour' => 0,
        'MaterialMarkup' => 0,
        'LabourMarkup' => 320,
        'Profit' => 320,
        'Margin' => 100,
        'TotalEx' => 320,
        'TotalInc' => 384,
        'Archived' => false,
        'DateModified' => '2026-05-28T14:18:16+01:00',
    ]);

    expect($prebuild->id)->toBe(18368)
        ->and($prebuild->groupId)->toBe(1235)
        ->and($prebuild->groupName)->toBe('Places for People')
        ->and($prebuild->parentGroupId)->toBe(36)
        ->and($prebuild->parentGroupName)->toBe('Maintenance & Monitoring Contracts')
        ->and($prebuild->partNo)->toBe('')
        ->and($prebuild->name)->toBe('PFP - Roller Shutter Maintenance')
        ->and($prebuild->taxCodeId)->toBe(3)
        ->and($prebuild->taxCode)->toBe('VAT')
        ->and($prebuild->taxRate)->toBe(20.0)
        ->and($prebuild->materials)->toBe(0.0)
        ->and($prebuild->labour)->toBe(0.0)
        ->and($prebuild->materialMarkup)->toBe(0.0)
        ->and($prebuild->labourMarkup)->toBe(320.0)
        ->and($prebuild->profit)->toBe(320.0)
        ->and($prebuild->margin)->toBe(100.0)
        ->and($prebuild->totalEx)->toBe(320.0)
        ->and($prebuild->totalInc)->toBe(384.0)
        ->and($prebuild->archived)->toBeFalse()
        ->and($prebuild->dateModified?->format(DATE_ATOM))
        ->toBe('2026-05-28T14:18:16+01:00');
});

it('defaults missing optional fields to null or zero', function (): void {
    $prebuild = Prebuild::fromArray([
        'ID' => 999,
        'Name' => 'minimal',
    ]);

    expect($prebuild->id)->toBe(999)
        ->and($prebuild->groupId)->toBeNull()
        ->and($prebuild->partNo)->toBeNull()
        ->and($prebuild->name)->toBe('minimal')
        ->and($prebuild->materials)->toBe(0.0)
        ->and($prebuild->archived)->toBeFalse()
        ->and($prebuild->dateModified)->toBeNull();
});
