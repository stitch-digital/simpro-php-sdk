<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

use DateTimeImmutable;

/**
 * Common DTO for file attachments.
 *
 * Used by jobs, quotes, customers, and other entities that support file attachments.
 *
 * Note: Different parent entities have different optional fields:
 * - Job/Quote/Lead attachments: `public`, `email`
 * - Catalog/Employee attachments: `default`
 * - All attachments can optionally include `base64Data` when requested with `?display=Base64`
 */
final readonly class Attachment
{
    public function __construct(
        public int $id,
        public ?string $filename = null,
        public ?string $mimeType = null,
        public ?int $fileSizeBytes = null,
        public ?Reference $folder = null,
        public ?DateTimeImmutable $dateAdded = null,
        public ?StaffReference $addedBy = null,
        // Context-specific fields (Job/Quote/Lead)
        public ?bool $public = null,
        public ?bool $email = null,
        // Context-specific fields (Catalog/Employee)
        public ?bool $default = null,
        // Optional - only when ?display=Base64 is used
        public ?string $base64Data = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            filename: $data['Filename'] ?? $data['FileName'] ?? null,
            mimeType: $data['MimeType'] ?? $data['ContentType'] ?? null,
            fileSizeBytes: isset($data['FileSizeBytes']) ? (int) $data['FileSizeBytes'] : (isset($data['Size']) ? (int) $data['Size'] : null),
            folder: isset($data['Folder']) && is_array($data['Folder']) ? Reference::fromArray($data['Folder']) : null,
            dateAdded: isset($data['DateAdded']) ? new DateTimeImmutable($data['DateAdded']) : (isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null),
            addedBy: isset($data['AddedBy']) && is_array($data['AddedBy']) ? StaffReference::fromArray($data['AddedBy']) : null,
            public: $data['Public'] ?? null,
            email: $data['Email'] ?? null,
            default: $data['Default'] ?? null,
            base64Data: $data['Base64Data'] ?? null,
        );
    }

    /**
     * Get the file extension from the filename.
     */
    public function extension(): ?string
    {
        if ($this->filename === null) {
            return null;
        }

        $ext = pathinfo($this->filename, PATHINFO_EXTENSION);

        return $ext !== '' ? strtolower($ext) : null;
    }

    /**
     * Check if this is an image attachment.
     */
    public function isImage(): bool
    {
        if ($this->mimeType !== null) {
            return str_starts_with($this->mimeType, 'image/');
        }

        $ext = $this->extension();

        return $ext !== null && in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true);
    }

    /**
     * Check if this attachment is publicly visible (Customer Portal).
     */
    public function isPublic(): bool
    {
        return $this->public === true;
    }

    /**
     * Check if this attachment is available for email/forms.
     */
    public function isEmailEnabled(): bool
    {
        return $this->email === true;
    }

    /**
     * Check if this is the default attachment (e.g., signature image).
     */
    public function isDefault(): bool
    {
        return $this->default === true;
    }

    /**
     * Check if base64 data is available.
     */
    public function hasBase64Data(): bool
    {
        return $this->base64Data !== null;
    }
}
