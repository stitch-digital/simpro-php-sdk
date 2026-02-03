<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * FitTime DTO.
 */
final readonly class FitTime
{
    public function __construct(
        public int $id,
        public string $name,
        public float $multiplier = 1.0,
        public int $displayOrder = 0,
        public bool $archived = false,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            multiplier: (float) ($data['Multiplier'] ?? 1.0),
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            archived: (bool) ($data['Archived'] ?? false),
        );
    }
}
