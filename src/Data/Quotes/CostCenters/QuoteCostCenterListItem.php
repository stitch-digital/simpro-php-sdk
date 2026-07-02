<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes\CostCenters;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class QuoteCostCenterListItem
{
    /**
     * @param  array<string, mixed>|null  $quote
     * @param  array<string, mixed>|null  $section
     * @param  array<string, mixed>|null  $total
     */
    public function __construct(
        public int $id,
        public ?Reference $costCenter,
        public ?string $name,
        public ?array $quote,
        public ?array $section,
        public ?int $displayOrder,
        public ?array $total,
        public ?DateTimeImmutable $dateModified,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            name: $data['Name'] ?? null,
            quote: $data['Quote'] ?? null,
            section: $data['Section'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            total: $data['Total'] ?? null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
