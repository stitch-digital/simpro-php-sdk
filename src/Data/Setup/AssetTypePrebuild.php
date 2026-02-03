<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Simpro\PhpSdk\Simpro\Data\Common\Money;

/**
 * AssetTypePrebuild DTO for nested Prebuild objects in Asset Type resources.
 */
final readonly class AssetTypePrebuild
{
    public function __construct(
        public ?int $id = null,
        public ?string $partNo = null,
        public ?string $name = null,
        public ?Money $addOnPrice = null,
        public int $displayOrder = 0,
        public bool $archived = false,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['ID']) ? (int) $data['ID'] : null,
            partNo: $data['PartNo'] ?? null,
            name: $data['Name'] ?? null,
            addOnPrice: isset($data['AddOnPrice']) ? Money::fromArray($data['AddOnPrice']) : null,
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            archived: (bool) ($data['Archived'] ?? false),
        );
    }
}
