<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\Catalogs;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class CatalogItem
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $partNo,
        public ?string $description,
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
            name: $data['Name'] ?? null,
            partNo: $data['PartNo'] ?? null,
            description: $data['Description'] ?? null,
            quantity: isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            unitCost: isset($data['UnitCost']) ? (float) $data['UnitCost'] : null,
            totalCost: isset($data['TotalCost']) ? (float) $data['TotalCost'] : null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }
}
