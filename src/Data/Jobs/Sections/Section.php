<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Sections;

use DateTimeImmutable;
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
        public ?bool $isVariation,
        public ?bool $isVariationRetention,
        public ?int $displayOrder,
        public ?DateTimeImmutable $dateModified,
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
            isVariation: $data['IsVariation'] ?? null,
            isVariationRetention: $data['IsVariationRetention'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            costCenters: isset($data['CostCenters']) ? array_map(
                fn (array $item) => SectionCostCenter::fromArray($item),
                $data['CostCenters']
            ) : null,
        );
    }
}
