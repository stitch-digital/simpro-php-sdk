<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\CustomerAssets;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class CustomerAssetListItem
{
    /**
     * @param  array<CustomerAssetServiceLevel>|null  $serviceLevels
     */
    public function __construct(
        public int $id,
        public ?Reference $assetType = null,
        public ?Reference $site = null,
        public ?array $serviceLevels = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            assetType: isset($data['AssetType']) && is_array($data['AssetType'])
                ? Reference::fromArray($data['AssetType'])
                : null,
            site: isset($data['Site']) && is_array($data['Site'])
                ? Reference::fromArray($data['Site'])
                : null,
            serviceLevels: isset($data['ServiceLevels']) && is_array($data['ServiceLevels'])
                ? array_map(fn (array $item) => CustomerAssetServiceLevel::fromArray($item), $data['ServiceLevels'])
                : null,
        );
    }
}
