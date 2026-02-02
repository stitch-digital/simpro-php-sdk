<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

use DateTimeImmutable;

/**
 * Reference to the quote or recurring job this job was converted from.
 */
final readonly class JobConvertedFrom
{
    public function __construct(
        public int $id,
        public ?string $type = null,
        public ?DateTimeImmutable $date = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            type: $data['Type'] ?? null,
            date: isset($data['Date']) ? new DateTimeImmutable($data['Date']) : null,
        );
    }
}
