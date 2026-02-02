<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Notes;

/**
 * DTO for customer note reference (type and number).
 *
 * Based on swagger: Reference object in customer notes.
 */
final readonly class CustomerNoteReference
{
    public function __construct(
        public ?string $type,
        public ?string $number,
        public string $text,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['Type'] ?? null,
            number: $data['Number'] ?? null,
            text: $data['Text'] ?? '',
        );
    }
}
