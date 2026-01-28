<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Simple attachment reference for notes.
 *
 * Notes have a simplified attachment structure with just href and filename.
 */
final readonly class NoteAttachment
{
    public function __construct(
        public ?string $href = null,
        public ?string $filename = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            href: $data['_href'] ?? null,
            filename: $data['FileName'] ?? $data['Filename'] ?? null,
        );
    }
}
