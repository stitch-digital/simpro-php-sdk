<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices;

use Simpro\PhpSdk\Simpro\Data\Jobs\JobTotal;

final readonly class RecurringInvoiceListItem
{
    public function __construct(
        public int $id,
        public ?string $description,
        public ?JobTotal $total,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            description: $data['Description'] ?? null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? JobTotal::fromArray($data['Total']) : null,
        );
    }
}
