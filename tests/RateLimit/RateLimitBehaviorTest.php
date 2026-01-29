<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\RateLimit\RateLimitBehavior;

test('it has sleep behavior', function () {
    expect(RateLimitBehavior::Sleep->value)->toBe('sleep');
});

test('it has throw behavior', function () {
    expect(RateLimitBehavior::Throw->value)->toBe('throw');
});

test('it can be created from value', function () {
    expect(RateLimitBehavior::from('sleep'))->toBe(RateLimitBehavior::Sleep);
    expect(RateLimitBehavior::from('throw'))->toBe(RateLimitBehavior::Throw);
});
