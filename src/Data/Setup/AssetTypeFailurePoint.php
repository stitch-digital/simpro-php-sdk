<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * AssetTypeFailurePoint DTO (detail response).
 */
final readonly class AssetTypeFailurePoint
{
    public function __construct(
        public int $id,
        public string $name,
        public int $displayOrder = 0,
        public ?string $standard = null,
        public ?AssetTypePrebuild $prebuild = null,
        public bool $isCritical = false,
        public ?string $severity = null,
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
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            standard: $data['Standard'] ?? null,
            prebuild: isset($data['Prebuild']) ? AssetTypePrebuild::fromArray($data['Prebuild']) : null,
            isCritical: (bool) ($data['IsCritical'] ?? false),
            severity: $data['Severity'] ?? null,
        );
    }
}
