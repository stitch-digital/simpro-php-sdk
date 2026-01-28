<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Common\Address;

it('creates address from array', function () {
    $address = Address::fromArray([
        'Address' => '123 Main St',
        'City' => 'Sydney',
        'State' => 'NSW',
        'PostalCode' => '2000',
        'Country' => 'Australia',
    ]);

    expect($address->address)->toBe('123 Main St')
        ->and($address->city)->toBe('Sydney')
        ->and($address->state)->toBe('NSW')
        ->and($address->postalCode)->toBe('2000')
        ->and($address->country)->toBe('Australia');
});

it('handles missing values', function () {
    $address = Address::fromArray([]);

    expect($address->address)->toBeNull()
        ->and($address->city)->toBeNull()
        ->and($address->state)->toBeNull()
        ->and($address->postalCode)->toBeNull()
        ->and($address->country)->toBeNull();
});

it('formats address as string', function () {
    $address = Address::fromArray([
        'Address' => '123 Main St',
        'City' => 'Sydney',
        'State' => 'NSW',
        'PostalCode' => '2000',
        'Country' => 'Australia',
    ]);

    expect($address->format())->toBe('123 Main St, Sydney, NSW, 2000, Australia');
});

it('formats address with custom separator', function () {
    $address = Address::fromArray([
        'Address' => '123 Main St',
        'City' => 'Sydney',
    ]);

    expect($address->format("\n"))->toBe("123 Main St\nSydney");
});

it('checks if address is empty', function () {
    $empty = Address::fromArray([]);
    $notEmpty = Address::fromArray(['City' => 'Sydney']);

    expect($empty->isEmpty())->toBeTrue()
        ->and($notEmpty->isEmpty())->toBeFalse();
});
