<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

/**
 * Reference to a linked variation job.
 *
 * Used in Job.linkedVariations and Job.convertedFromQuote.
 */
final readonly class JobVariationReference
{
    public function __construct(
        public int $id,
        public ?string $description = null,
        public ?JobTotal $total = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            description: $data['Description'] ?? null,
            total: isset($data['Total']) && is_array($data['Total'])
                ? JobTotal::fromArray($data['Total'])
                : null,
        );
    }
}
