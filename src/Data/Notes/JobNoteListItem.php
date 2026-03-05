<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Notes;

final readonly class JobNoteListItem
{
    public function __construct(
        public int $id,
        public ?string $subject = null,
        public ?NoteVisibility $visibility = null,
        public ?JobNoteJob $job = null,
        public ?string $href = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            subject: $data['Subject'] ?? null,
            visibility: ! empty($data['Visibility']) ? NoteVisibility::fromArray($data['Visibility']) : null,
            job: ! empty($data['Job']) ? JobNoteJob::fromArray($data['Job']) : null,
            href: $data['_href'] ?? null,
        );
    }
}
