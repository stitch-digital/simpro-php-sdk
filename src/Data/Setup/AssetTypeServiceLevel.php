<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * AssetTypeServiceLevel DTO (detail response).
 */
final readonly class AssetTypeServiceLevel
{
    public function __construct(
        public Reference $serviceLevel,
        public int $displayOrder = 0,
        public bool $isDefault = true,
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
            serviceLevel: Reference::fromArray($data['ServiceLevel']),
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            isDefault: (bool) ($data['IsDefault'] ?? true),
            prebuild: isset($data['Prebuild']) ? AssetTypePrebuild::fromArray($data['Prebuild']) : null,
        );
    }
}
