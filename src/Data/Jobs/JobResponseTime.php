<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

/**
 * Response time configuration for a job.
 */
final readonly class JobResponseTime
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?int $days = null,
        public ?int $hours = null,
        public ?int $minutes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
            days: isset($data['Days']) ? (int) $data['Days'] : null,
            hours: isset($data['Hours']) ? (int) $data['Hours'] : null,
            minutes: isset($data['Minutes']) ? (int) $data['Minutes'] : null,
        );
    }
}
