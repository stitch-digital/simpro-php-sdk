<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Data\Jobs\Logs\JobLog;

it('maps a status-change log payload', function () {
    $dto = JobLog::fromArray([
        'ID' => 3609009,
        'JobID' => 481833,
        'Message' => 'Updated Status from "Job: Engineer en Route" to "Job: In Progress"',
        'Staff' => ['ID' => 1118, 'Name' => 'Chris Bennett'],
        'DateLogged' => '2026-07-17T08:50:07+01:00',
    ]);

    expect($dto->id)->toBe(3609009)
        ->and($dto->jobId)->toBe(481833)
        ->and($dto->message)->toBe('Updated Status from "Job: Engineer en Route" to "Job: In Progress"')
        ->and($dto->staff)->toBeInstanceOf(StaffReference::class)
        ->and($dto->staff->id)->toBe(1118)
        ->and($dto->staff->name)->toBe('Chris Bennett')
        ->and($dto->dateLogged)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto->dateLogged->format('c'))->toBe('2026-07-17T08:50:07+01:00');
});

it('tolerates a null staff and missing optional keys', function () {
    $dto = JobLog::fromArray([
        'ID' => 1,
        'JobID' => 2,
        'Message' => 'Job created.',
        'Staff' => null,
        'DateLogged' => '2026-01-01T00:00:00+00:00',
    ]);

    expect($dto->staff)->toBeNull()->and($dto->jobId)->toBe(2);
});
