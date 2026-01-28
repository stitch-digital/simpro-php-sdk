<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Schedules;

final readonly class ScheduleListItem
{
    public function __construct(
        public int $id,
        public ?string $type,
        public ?string $subject,
        public ?string $date,
        public ?string $startTime,
        public ?string $endTime,
        public ?string $staff,
        public ?string $staffId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? null,
            subject: $data['Subject'] ?? null,
            date: $data['Date'] ?? null,
            startTime: $data['StartTime'] ?? null,
            endTime: $data['EndTime'] ?? null,
            staff: $data['Staff'] ?? null,
            staffId: $data['StaffID'] ?? null,
        );
    }
}
