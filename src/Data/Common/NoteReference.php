<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Reference information for a note, indicating the related entity.
 */
final readonly class NoteReference
{
    public function __construct(
        public ?string $type = null,
        public ?string $number = null,
        public ?string $text = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['Type'] ?? null,
            number: $data['Number'] ?? null,
            text: $data['Text'] ?? null,
        );
    }
}
