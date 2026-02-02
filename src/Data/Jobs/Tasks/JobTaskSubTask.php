<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Tasks;

final readonly class JobTaskSubTask
{
    public function __construct(
        public int $id,
        public ?string $description,
        public ?bool $isComplete,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            description: $data['Description'] ?? null,
            isComplete: $data['IsComplete'] ?? null,
        );
    }
}
