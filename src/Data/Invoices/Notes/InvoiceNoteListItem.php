<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices\Notes;

final readonly class InvoiceNoteListItem
{
    public function __construct(
        public int $id,
        public ?string $subject = null,
        public ?InvoiceNoteReference $reference = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            subject: $data['Subject'] ?? null,
            reference: ! empty($data['Reference']) ? InvoiceNoteReference::fromArray($data['Reference']) : null,
        );
    }
}
