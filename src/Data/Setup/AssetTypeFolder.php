<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * AssetTypeFolder DTO (detail response).
 */
final readonly class AssetTypeFolder
{
    public function __construct(
        public int $id,
        public string $name,
        public ?int $parentId = null,
        public ?Reference $parent = null,
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
            name: $data['Name'],
            parentId: isset($data['ParentID']) ? (int) $data['ParentID'] : null,
            parent: ! empty($data['Parent']) ? Reference::fromArray($data['Parent']) : null,
        );
    }
}
