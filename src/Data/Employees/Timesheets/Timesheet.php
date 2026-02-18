<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees\Timesheets;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class Timesheet
{
    public function __construct(
        public int $id,
        public ?DateTimeImmutable $date,
        public ?string $startTime,
        public ?string $finishTime,
        public ?float $totalHours,
        public ?Reference $job,
        public ?Reference $costCenter,
        public ?Reference $activity,
        public ?string $notes,
        public ?bool $billable,
        public ?bool $approved,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            date: ! empty($data['Date']) ? new DateTimeImmutable($data['Date']) : null,
            startTime: $data['StartTime'] ?? null,
            finishTime: $data['FinishTime'] ?? null,
            totalHours: isset($data['TotalHours']) ? (float) $data['TotalHours'] : null,
            job: ! empty($data['Job']) ? Reference::fromArray($data['Job']) : null,
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            activity: ! empty($data['Activity']) ? Reference::fromArray($data['Activity']) : null,
            notes: $data['Notes'] ?? null,
            billable: $data['Billable'] ?? null,
            approved: $data['Approved'] ?? null,
        );
    }
}
