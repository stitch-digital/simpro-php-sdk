<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Notes;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class JobNote
{
    /**
     * @param  array<JobNoteAttachment>|null  $attachments
     */
    public function __construct(
        public int $id,
        public ?string $subject,
        public ?string $note,
        public ?DateTimeImmutable $dateCreated,
        public ?DateTimeImmutable $followUpDate,
        public ?JobNoteVisibility $visibility,
        public ?StaffReference $assignTo,
        public ?array $attachments,
        public ?int $createdById,
        public ?string $createdByName,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            subject: $data['Subject'] ?? null,
            note: $data['Note'] ?? null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            followUpDate: isset($data['FollowUpDate']) ? new DateTimeImmutable($data['FollowUpDate']) : null,
            visibility: isset($data['Visibility']) ? JobNoteVisibility::fromArray($data['Visibility']) : null,
            assignTo: isset($data['AssignTo']) ? StaffReference::fromArray($data['AssignTo']) : null,
            attachments: isset($data['Attachments']) ? array_map(
                fn (array $item) => JobNoteAttachment::fromArray($item),
                $data['Attachments']
            ) : null,
            createdById: isset($data['CreatedBy']['ID']) ? (int) $data['CreatedBy']['ID'] : null,
            createdByName: $data['CreatedBy']['Name'] ?? null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
