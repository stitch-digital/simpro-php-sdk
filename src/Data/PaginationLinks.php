<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data;

final readonly class PaginationLinks
{
    public function __construct(
        public ?string $first = null,
        public ?string $last = null,
        public ?string $next = null,
        public ?string $prev = null,
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            first: $data['first'] ?? null,
            last: $data['last'] ?? null,
            next: $data['next'] ?? null,
            prev: $data['prev'] ?? null,
        );
    }
}
