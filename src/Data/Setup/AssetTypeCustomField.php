<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * AssetTypeCustomField DTO.
 */
final readonly class AssetTypeCustomField
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $type = null,
        public bool $required = false,
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
            name: $data['Name'] ?? null,
            type: $data['Type'] ?? null,
            required: (bool) ($data['Required'] ?? false),
        );
    }
}
