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
            batchId: (int) ($headers['Batch-ID'] ?? 0),
            resourceId: $headers['Resource-ID'] ?? 0,
            location: $headers['Location'] ?? null,
            body: $data['body'],
        );
    }

    public function isSuccessful(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    /**
     * The errors Simpro reported for this item.
     *
     * Simpro nests a failed item's body inside the 200 envelope as a JSON
     * *string*, not as a decoded object:
     *
     *   {"status":422,"headers":{"Batch-ID":414802},
     *    "body":"{\"errors\":[{\"path\":\"\\/ProjectManager\",\"message\":\"Must be an integer\",\"value\":{\"ID\":62}}]}"}
     *
     * so the string is decoded here. An already-decoded array body is read as
     * given, and anything else yields no errors.
     *
     * @return list<array{path: ?string, message: string, value: mixed}>
     */
    public function errors(): array
    {
        $body = is_string($this->body) ? json_decode($this->body, true) : $this->body;

        if (! is_array($body) || ! is_array($body['errors'] ?? null)) {
            return [];
        }

        $errors = [];

        foreach ($body['errors'] as $error) {
            if (! is_array($error) || ! is_string($error['message'] ?? null)) {
                continue;
            }

            $path = $error['path'] ?? null;

            $errors[] = [
                'path' => is_string($path) ? $path : null,
                'message' => $error['message'],
                'value' => $error['value'] ?? null,
            ];
        }

        return $errors;
    }

    /**
     * The first error message Simpro returned, or null when the item carries
     * no readable error.
     */
    public function errorMessage(): ?string
    {
        return $this->errors()[0]['message'] ?? null;
    }

    /**
     * True when this item failed because someone has the record open in
     * Simpro ("This job is currently locked by ..."). Unlike the other 422s,
     * which are payload validation failures, a lock is worth retrying.
     */
    public function isLocked(): bool
    {
        if ($this->status !== 422) {
            return false;
        }

        return str_contains(mb_strtolower($this->errorMessage() ?? ''), 'locked');
    }
}
