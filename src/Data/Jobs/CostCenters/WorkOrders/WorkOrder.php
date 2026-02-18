<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class WorkOrder
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?string $status,
        public ?int $assignedToId,
        public ?string $assignedToName,
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
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
