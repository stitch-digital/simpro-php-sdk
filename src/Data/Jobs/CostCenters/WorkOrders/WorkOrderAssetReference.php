<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class WorkOrderAssetReference
{
    public function __construct(
        public ?int $assetId = null,
        public ?Reference $assetType = null,
        public ?string $result = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            assetId: isset($data['Asset']['ID']) ? (int) $data['Asset']['ID'] : null,
            assetType: isset($data['Asset']['AssetType']) ? Reference::fromArray($data['Asset']['AssetType']) : null,
            result: $data['Result'] ?? null,
        );
    }
}
