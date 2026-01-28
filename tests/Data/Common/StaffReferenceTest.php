<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

it('creates staff reference from array', function () {
    $ref = StaffReference::fromArray([
        'ID' => 123,
        'Name' => 'John Smith',
        'Type' => 'employee',
        'TypeId' => 456,
    ]);

    expect($ref->id)->toBe(123)
        ->and($ref->name)->toBe('John Smith')
        ->and($ref->type)->toBe('employee')
        ->and($ref->typeId)->toBe(456);
});

it('handles missing optional fields', function () {
    $ref = StaffReference::fromArray(['ID' => 789]);

    expect($ref->id)->toBe(789)
        ->and($ref->name)->toBeNull()
        ->and($ref->type)->toBeNull()
        ->and($ref->typeId)->toBeNull();
});

it('checks if employee type', function () {
    $employee = StaffReference::fromArray(['ID' => 1, 'Type' => 'employee']);
    $contractor = StaffReference::fromArray(['ID' => 2, 'Type' => 'contractor']);

    expect($employee->isEmployee())->toBeTrue()
        ->and($contractor->isEmployee())->toBeFalse();
});

it('checks if contractor type', function () {
    $contractor = StaffReference::fromArray(['ID' => 1, 'Type' => 'contractor']);
    $employee = StaffReference::fromArray(['ID' => 2, 'Type' => 'employee']);

    expect($contractor->isContractor())->toBeTrue()
        ->and($employee->isContractor())->toBeFalse();
});

it('checks if plant type', function () {
    $plant = StaffReference::fromArray(['ID' => 1, 'Type' => 'plant']);
    $employee = StaffReference::fromArray(['ID' => 2, 'Type' => 'employee']);

    expect($plant->isPlant())->toBeTrue()
        ->and($employee->isPlant())->toBeFalse();
});
