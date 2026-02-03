<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * AssetTypeFileListItem DTO (list response).
 */
final readonly class AssetTypeFileListItem
{
    public function __construct(
        public string $id,
        public string $filename,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['ID'],
            filename: $data['Filename'],
        );
    }
}
