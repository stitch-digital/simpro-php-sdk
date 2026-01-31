<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Schedules;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for schedule time blocks.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/schedules/
 */
final readonly class ScheduleBlock
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
            scheduleRate: isset($data['ScheduleRate']) ? Reference::fromArray($data['ScheduleRate']) : null,
        );
    }
}
