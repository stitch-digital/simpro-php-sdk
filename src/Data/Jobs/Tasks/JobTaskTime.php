<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Tasks;

use DateTimeImmutable;

final readonly class JobTaskTime
{
    public function __construct(
        public ?DateTimeImmutable $startDate,
        public ?string $startTime,
        public ?DateTimeImmutable $endDate,
        public ?string $endTime,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            startDate: ! empty($data['StartDate']) ? new DateTimeImmutable($data['StartDate']) : null,
            startTime: $data['StartTime'] ?? null,
            endDate: ! empty($data['EndDate']) ? new DateTimeImmutable($data['EndDate']) : null,
            endTime: $data['EndTime'] ?? null,
        );
    }
}
