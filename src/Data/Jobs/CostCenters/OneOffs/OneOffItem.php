<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\OneOffs;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class OneOffItem
{
    public function __construct(
        public int $id,
        public ?string $description,
        public ?float $quantity,
        public ?float $unitCost,
        public ?float $totalCost,
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
            description: $data['Description'] ?? null,
            quantity: isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            unitCost: isset($data['UnitCost']) ? (float) $data['UnitCost'] : null,
            totalCost: isset($data['TotalCost']) ? (float) $data['TotalCost'] : null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
