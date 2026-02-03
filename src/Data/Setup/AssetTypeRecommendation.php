<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * AssetTypeRecommendation DTO (detail response).
 */
final readonly class AssetTypeRecommendation
{
    public function __construct(
        public int $id,
        public string $name,
        public float $chargeRate = 0.0,
        public ?AssetTypePrebuild $prebuild = null,
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
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'],
            chargeRate: (float) ($data['ChargeRate'] ?? 0.0),
            prebuild: isset($data['Prebuild']) ? AssetTypePrebuild::fromArray($data['Prebuild']) : null,
        );
    }
}
