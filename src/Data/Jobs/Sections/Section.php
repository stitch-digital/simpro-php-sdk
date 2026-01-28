<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Sections;

use Saloon\Http\Response;

final readonly class Section
{
    /**
     * @param  array<SectionCostCenter>|null  $costCenters
     */
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?int $displayOrder,
        public ?array $costCenters,
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
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            costCenters: isset($data['CostCenters']) ? array_map(
                fn (array $item) => SectionCostCenter::fromArray($item),
                $data['CostCenters']
            ) : null,
        );
    }
}
