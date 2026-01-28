<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Labor;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class LaborItem
{
    public function __construct(
        public int $id,
        public ?string $description,
        public ?int $laborRateId,
        public ?string $laborRateName,
        public ?float $quantity,
        public ?float $hours,
        public ?float $unitCost,
        public ?float $totalCost,
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
            description: $data['Description'] ?? null,
            laborRateId: isset($data['LaborRate']['ID']) ? (int) $data['LaborRate']['ID'] : null,
            laborRateName: $data['LaborRate']['Name'] ?? null,
            quantity: isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            hours: isset($data['Hours']) ? (float) $data['Hours'] : null,
            unitCost: isset($data['UnitCost']) ? (float) $data['UnitCost'] : null,
            totalCost: isset($data['TotalCost']) ? (float) $data['TotalCost'] : null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
