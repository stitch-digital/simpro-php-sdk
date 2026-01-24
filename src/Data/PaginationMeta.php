<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data;

final readonly class PaginationMeta
{
    public function __construct(
        public ?int $count = null,
        public ?int $limit = null,
        public ?int $offset = null,
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        $pagination = $data['pagination'] ?? [];

        return new self(
            count: $pagination['count'] ?? null,
            limit: $pagination['limit'] ?? null,
            offset: $pagination['offset'] ?? null,
        );
    }
}
