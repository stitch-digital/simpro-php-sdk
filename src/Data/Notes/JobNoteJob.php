<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Notes;

final readonly class JobNoteJob
{
    public function __construct(
        public int $id,
        public ?string $name = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? null,
        );
    }
}
