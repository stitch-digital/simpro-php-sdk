<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Schedules;

final readonly class ScheduleBlock
{
    public function __construct(
        public float $hrs,
        public string $startTime,
        public string $iso8601StartTime,
        public string $endTime,
        public string $iso8601EndTime,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            hrs: (float) ($data['Hrs'] ?? 0),
            startTime: $data['StartTime'] ?? '',
            iso8601StartTime: $data['ISO8601StartTime'] ?? '',
            endTime: $data['EndTime'] ?? '',
            iso8601EndTime: $data['ISO8601EndTime'] ?? '',
        );
    }
}
