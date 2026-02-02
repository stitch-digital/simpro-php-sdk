<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Tasks;

final readonly class JobTaskEmailNotifications
{
    public function __construct(
        public ?bool $onDueDate,
        public ?bool $onOverdue,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            onDueDate: $data['OnDueDate'] ?? null,
            onOverdue: $data['OnOverdue'] ?? null,
        );
    }
}
