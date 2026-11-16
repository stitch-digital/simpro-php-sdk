<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices;

final readonly class RecurringInvoiceContact
{
    public function __construct(
        public int $id,
        public ?string $givenName,
        public ?string $familyName,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
        );
    }
}
