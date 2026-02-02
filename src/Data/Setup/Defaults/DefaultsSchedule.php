<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsSchedule
{
    public function __construct(
        public string $workWeekStart,
        public string $scheduleFormat,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            workWeekStart: $data['WorkWeekStart'] ?? '',
            scheduleFormat: $data['ScheduleFormat'] ?? '',
        );
    }
}
