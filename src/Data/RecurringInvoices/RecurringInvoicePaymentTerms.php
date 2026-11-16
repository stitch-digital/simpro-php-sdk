<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices;

/**
 * The deprecated Days/Type payment terms pair Simpro still returns alongside PaymentTermID.
 */
final readonly class RecurringInvoicePaymentTerms
{
    public function __construct(
        public int $days,
        public ?string $type,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            days: (int) ($data['Days'] ?? 0),
            type: $data['Type'] ?? null,
        );
    }
}
