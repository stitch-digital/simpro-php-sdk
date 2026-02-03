<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Quote archive reason DTO.
 */
final readonly class QuoteArchiveReason
{
    public function __construct(
        public int $id,
        public string $archiveReason,
        public int $displayOrder = 0,
        public bool $archived = false,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            archiveReason: $data['ArchiveReason'] ?? '',
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            archived: (bool) ($data['Archived'] ?? false),
        );
    }

    public static function fromResponse(Response $response): self
    {
        /** @var array<string, mixed> $data */
        $data = $response->json();

        return self::fromArray($data);
    }
}
