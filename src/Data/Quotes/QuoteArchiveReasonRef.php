<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

final readonly class QuoteArchiveReasonRef
{
    public function __construct(
        public int $id,
        public ?string $archiveReason,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            archiveReason: $data['ArchiveReason'] ?? null,
        );
    }
}
