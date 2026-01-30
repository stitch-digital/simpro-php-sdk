<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * AssetTypeFile DTO.
 */
final readonly class AssetTypeFile
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $url = null,
        public ?int $size = null,
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
            url: $data['Url'] ?? null,
            size: isset($data['Size']) ? (int) $data['Size'] : null,
        );
    }
}
