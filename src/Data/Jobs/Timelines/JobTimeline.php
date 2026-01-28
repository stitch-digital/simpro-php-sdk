<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Timelines;

use DateTimeImmutable;

final readonly class JobTimeline
{
    public function __construct(
        public int $id,
        public ?string $type,
        public ?string $description,
        public ?int $userId,
        public ?string $userName,
        public ?DateTimeImmutable $dateCreated,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? null,
            description: $data['Description'] ?? null,
            userId: isset($data['User']['ID']) ? (int) $data['User']['ID'] : null,
            userName: $data['User']['Name'] ?? null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
