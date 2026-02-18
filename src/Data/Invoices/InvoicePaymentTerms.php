<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

use DateTimeImmutable;

final readonly class InvoicePaymentTerms
{
    public function __construct(
        public ?int $days = null,
        public ?string $type = null,
        public ?DateTimeImmutable $dueDate = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            days: isset($data['Days']) ? (int) $data['Days'] : null,
            type: $data['Type'] ?? null,
            dueDate: ! empty($data['DueDate']) ? new DateTimeImmutable($data['DueDate']) : null,
        );
    }
}
