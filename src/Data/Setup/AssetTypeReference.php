<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * AssetTypeReference DTO for Section/Standard nested object.
 */
final readonly class AssetTypeReference
{
    public function __construct(
        public ?string $section = null,
        public ?string $standard = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            section: $data['Section'] ?? null,
            standard: $data['Standard'] ?? null,
        );
    }
}
