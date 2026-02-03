<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ActivitySchedules;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for activity schedule time blocks.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/activitySchedules/{scheduleID}
 */
final readonly class ActivityScheduleBlock
{
    public function __construct(
        public ?float $hrs,
        public ?string $startTime,
        public ?DateTimeImmutable $iso8601StartTime,
        public ?string $endTime,
        public ?DateTimeImmutable $iso8601EndTime,
        public ?Reference $scheduleRate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            hrs: isset($data['Hrs']) ? (float) $data['Hrs'] : null,
            startTime: $data['StartTime'] ?? null,
            iso8601StartTime: isset($data['ISO8601StartTime']) ? new DateTimeImmutable($data['ISO8601StartTime']) : null,
            endTime: $data['EndTime'] ?? null,
            iso8601EndTime: isset($data['ISO8601EndTime']) ? new DateTimeImmutable($data['ISO8601EndTime']) : null,
            scheduleRate: isset($data['ScheduleRate']['ID']) ? Reference::fromArray($data['ScheduleRate']) : null,
        );
    }
}
