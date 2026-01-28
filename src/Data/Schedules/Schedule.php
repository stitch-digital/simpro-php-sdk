<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Schedules;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class Schedule
{
    public function __construct(
        public int $id,
        public ?string $type,
        public ?string $subject,
        public ?DateTimeImmutable $date,
        public ?string $startTime,
        public ?string $endTime,
        public ?ScheduleStaff $staff,
        public ?ScheduleJob $job,
        public ?string $notes,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? null,
            subject: $data['Subject'] ?? null,
            date: isset($data['Date']) ? new DateTimeImmutable($data['Date']) : null,
            startTime: $data['StartTime'] ?? null,
            endTime: $data['EndTime'] ?? null,
            staff: isset($data['Staff']) ? ScheduleStaff::fromArray($data['Staff']) : null,
            job: isset($data['Job']) ? ScheduleJob::fromArray($data['Job']) : null,
            notes: $data['Notes'] ?? null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
