<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

final readonly class TeamAvailability
{
    public function __construct(
        public string $startDay,
        public string $startTime,
        public string $endDay,
        public string $endTime,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            startDay: $data['StartDay'] ?? '',
            startTime: $data['StartTime'] ?? '',
            endDay: $data['EndDay'] ?? '',
            endTime: $data['EndTime'] ?? '',
        );
    }
}
