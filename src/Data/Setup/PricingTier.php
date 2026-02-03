<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * PricingTier DTO.
 */
final readonly class PricingTier
{
    /**
     * @param  array<ScaledTierPricing>  $scaledTierPricing
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $priceBased = 'Buy',
        public float $defaultMarkup = 0.0,
        public bool $default = false,
        public string $tierType = 'Quantity',
        public array $scaledTierPricing = [],
        public int $displayOrder = 0,
        public bool $archived = false,
        public ?string $dateModified = null,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $scaledTierPricing = [];
        if (isset($data['ScaledTierPricing']) && is_array($data['ScaledTierPricing'])) {
            $scaledTierPricing = array_map(
                fn (array $item): ScaledTierPricing => ScaledTierPricing::fromArray($item),
                $data['ScaledTierPricing']
            );
        }

        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            priceBased: $data['PriceBased'] ?? 'Buy',
            defaultMarkup: (float) ($data['DefaultMarkup'] ?? 0.0),
            default: (bool) ($data['Default'] ?? false),
            tierType: $data['TierType'] ?? 'Quantity',
            scaledTierPricing: $scaledTierPricing,
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            archived: (bool) ($data['Archived'] ?? false),
            dateModified: $data['DateModified'] ?? null,
        );
    }
}
