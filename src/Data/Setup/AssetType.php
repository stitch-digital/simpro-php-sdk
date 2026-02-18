<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * AssetType DTO (detail response).
 */
final readonly class AssetType
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?AssetTypeReference $reference = null,
        public int $regType = 0,
        public ?Reference $jobCostCenter = null,
        public ?Reference $quoteCostCenter = null,
        public ?StaffReference $defaultTechnician = null,
        public ?string $description = null,
        public bool $archived = false,
        /** @var array<AssetTypeServiceLevel>|null */
        public ?array $serviceLevels = null,
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
            name: $data['Name'] ?? null,
            reference: ! empty($data['Reference']) ? AssetTypeReference::fromArray($data['Reference']) : null,
            regType: (int) ($data['RegType'] ?? 0),
            jobCostCenter: ! empty($data['JobCostCenter']) ? Reference::fromArray($data['JobCostCenter']) : null,
            quoteCostCenter: ! empty($data['QuoteCostCenter']) ? Reference::fromArray($data['QuoteCostCenter']) : null,
            defaultTechnician: ! empty($data['DefaultTechnician']) ? StaffReference::fromArray($data['DefaultTechnician']) : null,
            description: $data['Description'] ?? null,
            archived: (bool) ($data['Archived'] ?? false),
            serviceLevels: ! empty($data['ServiceLevels'])
                ? array_map(fn (array $item): AssetTypeServiceLevel => AssetTypeServiceLevel::fromArray($item), $data['ServiceLevels'])
                : null,
        );
    }
}
