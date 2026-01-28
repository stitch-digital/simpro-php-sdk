<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Notes;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class JobNote
{
    public function __construct(
        public int $id,
        public ?string $subject,
        public ?string $note,
        public ?int $createdById,
        public ?string $createdByName,
        public ?DateTimeImmutable $dateCreated,
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
            createdById: isset($data['CreatedBy']['ID']) ? (int) $data['CreatedBy']['ID'] : null,
            createdByName: $data['CreatedBy']['Name'] ?? null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
