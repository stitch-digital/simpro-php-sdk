<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Schedules;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class CostCenterSchedule
{
    public function __construct(
        public int $id,
        public ?int $staffId,
        public ?string $staffName,
        public ?DateTimeImmutable $startDate,
        public ?DateTimeImmutable $endDate,
        public ?string $startTime,
        public ?string $endTime,
        public ?string $notes,
        public ?DateTimeImmutable $dateCreated,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            staffId: isset($data['Staff']['ID']) ? (int) $data['Staff']['ID'] : null,
            staffName: $data['Staff']['Name'] ?? null,
            startDate: isset($data['StartDate']) ? new DateTimeImmutable($data['StartDate']) : null,
            endDate: isset($data['EndDate']) ? new DateTimeImmutable($data['EndDate']) : null,
            startTime: $data['StartTime'] ?? null,
            endTime: $data['EndTime'] ?? null,
            notes: $data['Notes'] ?? null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
