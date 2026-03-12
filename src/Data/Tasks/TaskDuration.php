<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Tasks;

final readonly class TaskDuration
{
    public function __construct(
        public int $hours = 0,
        public int $minutes = 0,
        public int $seconds = 0,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            hours: (int) ($data['Hours'] ?? 0),
            minutes: (int) ($data['Minutes'] ?? 0),
            seconds: (int) ($data['Seconds'] ?? 0),
        );
    }
}
