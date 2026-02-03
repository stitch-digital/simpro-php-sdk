<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * AssetTypeServiceLevelListItem DTO (list response).
 */
final readonly class AssetTypeServiceLevelListItem
{
    public function __construct(
        public Reference $serviceLevel,
        public int $displayOrder = 0,
        public bool $isDefault = true,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceLevel: Reference::fromArray($data['ServiceLevel']),
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            isDefault: (bool) ($data['IsDefault'] ?? true),
        );
    }
}
