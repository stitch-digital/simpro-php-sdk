<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices\Notes;

final readonly class InvoiceNoteReference
{
    public function __construct(
        public ?string $number = null,
        public ?string $text = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            number: $data['Number'] ?? null,
            text: $data['Text'] ?? null,
        );
    }
}
