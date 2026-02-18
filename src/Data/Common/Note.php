<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

use DateTimeImmutable;

/**
 * Common DTO for notes/comments.
 *
 * Used by jobs, quotes, customers, and other entities that support notes.
 */
final readonly class Note
{
    /**
     * @param  array<NoteAttachment>|null  $attachments
     */
    public function __construct(
        public int $id,
        public ?string $subject = null,
        public ?string $note = null,
        public ?DateTimeImmutable $dateCreated = null,
        public ?DateTimeImmutable $followUpDate = null,
        public ?StaffReference $assignTo = null,
        public ?StaffReference $submittedBy = null,
        public ?NoteReference $reference = null,
        public ?array $attachments = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            subject: $data['Subject'] ?? null,
            note: $data['Note'] ?? $data['Text'] ?? $data['Content'] ?? null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            followUpDate: ! empty($data['FollowUpDate']) ? new DateTimeImmutable($data['FollowUpDate']) : null,
            assignTo: ! empty($data['AssignTo']) && is_array($data['AssignTo']) ? StaffReference::fromArray($data['AssignTo']) : null,
            submittedBy: ! empty($data['SubmittedBy']) && is_array($data['SubmittedBy']) ? StaffReference::fromArray($data['SubmittedBy']) : null,
            reference: ! empty($data['Reference']) && is_array($data['Reference']) ? NoteReference::fromArray($data['Reference']) : null,
            attachments: isset($data['Attachments']) ? array_map(
                fn (array $item) => NoteAttachment::fromArray($item),
                $data['Attachments']
            ) : null,
        );
    }

    /**
     * Check if this note has content.
     */
    public function hasContent(): bool
    {
        return $this->note !== null && $this->note !== '';
    }

    /**
     * Get a truncated preview of the note text.
     */
    public function preview(int $length = 100): string
    {
        if ($this->note === null) {
            return '';
        }

        // Strip HTML tags for preview
        $text = strip_tags($this->note);

        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length).'...';
    }

    /**
     * Check if this note has a follow-up date.
     */
    public function hasFollowUp(): bool
    {
        return $this->followUpDate !== null;
    }

    /**
     * Check if this note has attachments.
     */
    public function hasAttachments(): bool
    {
        return $this->attachments !== null && count($this->attachments) > 0;
    }
}
