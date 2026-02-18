<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

use DateTimeImmutable;

final readonly class QuoteConvertedFromLead
{
    public function __construct(
        public int $id,
        public ?string $leadName,
        public ?DateTimeImmutable $dateCreated,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            leadName: $data['LeadName'] ?? null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
