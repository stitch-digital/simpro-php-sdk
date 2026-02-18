<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices\CreditNotes;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class CreditNoteJob
{
    public function __construct(
        public int $id,
        public ?Reference $site = null,
        public ?Reference $salesperson = null,
        public ?CreditNoteTotal $total = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            site: ! empty($data['Site']) ? Reference::fromArray($data['Site']) : null,
            salesperson: ! empty($data['Salesperson']) ? Reference::fromArray($data['Salesperson']) : null,
            total: ! empty($data['Total']) ? CreditNoteTotal::fromArray($data['Total']) : null,
        );
    }
}
