<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * AssetTypeFile DTO (detail response).
 */
final readonly class AssetTypeFile
{
    public function __construct(
        public string $id,
        public string $filename,
        public ?Reference $folder = null,
        public ?string $mimeType = null,
        public ?int $fileSizeBytes = null,
        public ?DateTimeImmutable $dateAdded = null,
        public ?StaffReference $addedBy = null,
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
            id: (string) $data['ID'],
            filename: $data['Filename'],
            folder: ! empty($data['Folder']) ? Reference::fromArray($data['Folder']) : null,
            mimeType: $data['MimeType'] ?? null,
            fileSizeBytes: isset($data['FileSizeBytes']) ? (int) $data['FileSizeBytes'] : null,
            dateAdded: ! empty($data['DateAdded']) ? new DateTimeImmutable($data['DateAdded']) : null,
            addedBy: ! empty($data['AddedBy']) ? StaffReference::fromArray($data['AddedBy']) : null,
        );
    }
}
