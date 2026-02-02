<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Notes;

/**
 * DTO for customer note list item (minimal fields).
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/notes/
 */
final readonly class CustomerNoteListItem
{
    public function __construct(
        public int $id,
        public ?string $subject,
        public CustomerNoteReference $reference,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            subject: $data['Subject'] ?? null,
            reference: CustomerNoteReference::fromArray($data['Reference'] ?? []),
        );
    }
}
