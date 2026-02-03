<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * Quote archive reason list item DTO.
 */
final readonly class QuoteArchiveReasonListItem
{
    public function __construct(
        public int $id,
        public string $archiveReason,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            archiveReason: $data['ArchiveReason'] ?? '',
        );
    }
}
