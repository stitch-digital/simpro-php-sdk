<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoiceCostCenters;

use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoiceType;

final readonly class RecurringInvoiceCostCenterReference
{
    public function __construct(
        public int $id,
        public ?RecurringInvoiceType $type,
        public ?string $name,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            type: isset($data['Type']) ? RecurringInvoiceType::tryFrom($data['Type']) : null,
            name: $data['Name'] ?? null,
        );
    }
}
