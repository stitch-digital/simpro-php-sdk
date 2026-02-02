<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Notes;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * DTO for a single customer note (detailed view).
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/notes/{noteID}
 */
final readonly class CustomerNote
{
    /**
     * @param  array<CustomerNoteAttachment>  $attachments
     */
    public function __construct(
        public int $id,
        public ?string $subject,
        public ?string $note,
        public DateTimeImmutable $dateCreated,
        public ?DateTimeImmutable $followUpDate,
        public array $attachments,
        public ?StaffReference $assignTo,
        public ?StaffReference $submittedBy,
        public CustomerNoteReference $reference,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            subject: $data['Subject'] ?? null,
            note: $data['Note'] ?? null,
            dateCreated: new DateTimeImmutable($data['DateCreated']),
            followUpDate: isset($data['FollowUpDate']) ? new DateTimeImmutable($data['FollowUpDate']) : null,
            attachments: isset($data['Attachments']) ? array_map(
                fn (array $item) => CustomerNoteAttachment::fromArray($item),
                $data['Attachments']
            ) : [],
            assignTo: isset($data['AssignTo']) ? StaffReference::fromArray($data['AssignTo']) : null,
            submittedBy: isset($data['SubmittedBy']) ? StaffReference::fromArray($data['SubmittedBy']) : null,
            reference: CustomerNoteReference::fromArray($data['Reference'] ?? []),
        );
    }
}
