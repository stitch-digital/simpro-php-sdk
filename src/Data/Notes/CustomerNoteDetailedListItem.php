<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Notes;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\NoteAttachment;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class CustomerNoteDetailedListItem
{
    /**
     * @param  array<NoteAttachment>  $attachments
     */
    public function __construct(
        public int $id,
        public ?string $subject = null,
        public ?NoteVisibility $visibility = null,
        public ?CustomerNoteCustomer $customer = null,
        public ?string $href = null,
        public ?string $note = null,
        public ?DateTimeImmutable $dateCreated = null,
        public ?DateTimeImmutable $followUpDate = null,
        public array $attachments = [],
        public ?StaffReference $assignTo = null,
        public ?StaffReference $submittedBy = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            subject: $data['Subject'] ?? null,
            visibility: ! empty($data['Visibility']) ? NoteVisibility::fromArray($data['Visibility']) : null,
            customer: ! empty($data['Customer']) ? CustomerNoteCustomer::fromArray($data['Customer']) : null,
            href: $data['_href'] ?? null,
            note: $data['Note'] ?? null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            followUpDate: ! empty($data['FollowUpDate']) ? new DateTimeImmutable($data['FollowUpDate']) : null,
            attachments: isset($data['Attachments']) ? array_map(
                fn (array $item) => NoteAttachment::fromArray($item),
                $data['Attachments']
            ) : [],
            assignTo: ! empty($data['AssignTo']) ? StaffReference::fromArray($data['AssignTo']) : null,
            submittedBy: ! empty($data['SubmittedBy']) ? StaffReference::fromArray($data['SubmittedBy']) : null,
        );
    }
}
