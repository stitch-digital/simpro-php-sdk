<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ServiceFees;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class ServiceFee
{
    public function __construct(
        public int $id,
        public ?string $description,
        public ?int $serviceFeeId,
        public ?string $serviceFeeName,
        public ?float $quantity,
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
            serviceFeeId: isset($data['ServiceFee']['ID']) ? (int) $data['ServiceFee']['ID'] : null,
            serviceFeeName: $data['ServiceFee']['Name'] ?? null,
            quantity: isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            unitCost: isset($data['UnitCost']) ? (float) $data['UnitCost'] : null,
            totalCost: isset($data['TotalCost']) ? (float) $data['TotalCost'] : null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
