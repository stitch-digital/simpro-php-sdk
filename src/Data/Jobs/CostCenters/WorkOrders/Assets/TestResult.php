<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders\Assets;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class TestResult
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $status,
        public ?DateTimeImmutable $passedDate,
        public ?DateTimeImmutable $failedDate,
        public ?int $testTypeId,
        public ?string $testTypeName,
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
            name: $data['Name'] ?? null,
            status: $data['Status'] ?? null,
            passedDate: isset($data['PassedDate']) ? new DateTimeImmutable($data['PassedDate']) : null,
            failedDate: isset($data['FailedDate']) ? new DateTimeImmutable($data['FailedDate']) : null,
            testTypeId: isset($data['TestType']['ID']) ? (int) $data['TestType']['ID'] : null,
            testTypeName: $data['TestType']['Name'] ?? null,
            notes: $data['Notes'] ?? null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
