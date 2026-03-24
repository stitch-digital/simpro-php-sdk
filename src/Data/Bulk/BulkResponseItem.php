<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Bulk;

final readonly class BulkResponseItem
{
    public function __construct(
        public int $status,
        public int $batchId,
        public int|string $resourceId,
        public ?string $location,
        public mixed $body,
    ) {}

    /**
     * @param  array{status: int, headers: array<string, mixed>, body: mixed}  $data
     */
    public static function fromArray(array $data): self
    {
        $headers = $data['headers'];

        return new self(
            status: $data['status'],
            batchId: (int) $headers['Batch-ID'],
            resourceId: $headers['Resource-ID'],
            location: $headers['Location'] ?? null,
            body: $data['body'],
        );
    }

    public function isSuccessful(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }
}
