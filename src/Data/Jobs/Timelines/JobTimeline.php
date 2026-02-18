<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Timelines;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class JobTimeline
{
    public function __construct(
        public ?string $type,
        public ?string $message,
        public ?StaffReference $staff,
        public ?DateTimeImmutable $date,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['Type'] ?? null,
            message: $data['Message'] ?? null,
            staff: ! empty($data['Staff']) ? StaffReference::fromArray($data['Staff']) : null,
            date: ! empty($data['Date']) ? new DateTimeImmutable($data['Date']) : null,
        );
    }
}
