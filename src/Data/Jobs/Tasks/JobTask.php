<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Tasks;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class JobTask
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?string $status,
        public ?int $assignedToId,
        public ?string $assignedToName,
        public ?DateTimeImmutable $dueDate,
        public ?DateTimeImmutable $completedDate,
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
            name: $data['Name'] ?? null,
            description: $data['Description'] ?? null,
            status: $data['Status'] ?? null,
            assignedToId: isset($data['AssignedTo']['ID']) ? (int) $data['AssignedTo']['ID'] : null,
            assignedToName: $data['AssignedTo']['Name'] ?? null,
            dueDate: isset($data['DueDate']) ? new DateTimeImmutable($data['DueDate']) : null,
            completedDate: isset($data['CompletedDate']) ? new DateTimeImmutable($data['CompletedDate']) : null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
