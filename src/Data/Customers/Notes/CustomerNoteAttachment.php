<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Notes;

/**
 * DTO for customer note attachment.
 *
 * Based on swagger: Attachments array in customer notes.
 */
final readonly class CustomerNoteAttachment
{
    public function __construct(
        public string $href,
        public string $fileName,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            href: $data['_href'] ?? '',
            fileName: $data['FileName'] ?? '',
        );
    }
}
