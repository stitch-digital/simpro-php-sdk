<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters;

use Saloon\Http\Response;

final readonly class CostCenter
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?int $costCenterId,
        public ?string $costCenterName,
        public ?int $displayOrder,
        public ?float $totalExTax,
        public ?float $totalTax,
        public ?float $totalIncTax,
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
            costCenterId: isset($data['CostCenter']['ID']) ? (int) $data['CostCenter']['ID'] : null,
            costCenterName: $data['CostCenter']['Name'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            totalExTax: isset($data['Total']['ExTax']) ? (float) $data['Total']['ExTax'] : null,
            totalTax: isset($data['Total']['Tax']) ? (float) $data['Total']['Tax'] : null,
            totalIncTax: isset($data['Total']['IncTax']) ? (float) $data['Total']['IncTax'] : null,
        );
    }
}
