<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Tasks;

final readonly class JobTaskStatus
{
    public function __construct(
        public int $id,
        public ?string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
        );
    }
}
