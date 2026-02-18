<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes\Notes;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class QuoteNote
{
    public function __construct(
        public int $id,
        public ?string $subject,
        public ?string $note,
        public ?DateTimeImmutable $dateCreated,
        public ?DateTimeImmutable $followUpDate,
        public ?StaffReference $assignTo,
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
            id: (int) ($data['ID'] ?? 0),
            subject: $data['Subject'] ?? null,
            note: $data['Note'] ?? null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            followUpDate: ! empty($data['FollowUpDate']) ? new DateTimeImmutable($data['FollowUpDate']) : null,
            assignTo: ! empty($data['AssignTo']) ? StaffReference::fromArray($data['AssignTo']) : null,
            createdById: isset($data['CreatedBy']['ID']) ? (int) $data['CreatedBy']['ID'] : null,
            createdByName: $data['CreatedBy']['Name'] ?? null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
