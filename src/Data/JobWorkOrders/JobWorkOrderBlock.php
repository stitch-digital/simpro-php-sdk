<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\JobWorkOrders;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class JobWorkOrderBlock
{
    public function __construct(
        public ?float $hrs = null,
        public ?string $startTime = null,
        public ?DateTimeImmutable $iso8601StartTime = null,
        public ?string $endTime = null,
        public ?DateTimeImmutable $iso8601EndTime = null,
        public ?Reference $scheduleRate = null,
    ) {}

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
