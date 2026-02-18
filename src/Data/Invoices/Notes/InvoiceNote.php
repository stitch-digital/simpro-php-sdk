<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices\Notes;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class InvoiceNote
{
    public function __construct(
        public int $id,
        public ?string $subject = null,
        public ?string $note = null,
        public ?DateTimeImmutable $dateCreated = null,
        public ?DateTimeImmutable $followUpDate = null,
        public ?StaffReference $assignTo = null,
        public ?StaffReference $submittedBy = null,
        public ?InvoiceNoteReference $reference = null,
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
            id: $data['ID'],
            subject: $data['Subject'] ?? null,
            note: $data['Note'] ?? null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            followUpDate: ! empty($data['FollowUpDate']) ? new DateTimeImmutable($data['FollowUpDate']) : null,
            assignTo: ! empty($data['AssignTo']) ? StaffReference::fromArray($data['AssignTo']) : null,
            submittedBy: ! empty($data['SubmittedBy']) ? StaffReference::fromArray($data['SubmittedBy']) : null,
            reference: ! empty($data['Reference']) ? InvoiceNoteReference::fromArray($data['Reference']) : null,
        );
    }
}
