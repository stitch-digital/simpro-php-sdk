<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class WorkOrderAssetDetailed
{
    /**
     * @param  array<mixed>  $failurePoints
     * @param  array<TestReading>  $testReadings
     */
    public function __construct(
        public ?int $assetId = null,
        public ?Reference $assetType = null,
        public ?Reference $serviceLevel = null,
        public ?string $result = null,
        public ?string $notes = null,
        public array $failurePoints = [],
        public array $testReadings = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            assetId: isset($data['Asset']['ID']) ? (int) $data['Asset']['ID'] : null,
            assetType: isset($data['Asset']['AssetType']) ? Reference::fromArray($data['Asset']['AssetType']) : null,
            serviceLevel: isset($data['ServiceLevel']) ? Reference::fromArray($data['ServiceLevel']) : null,
            result: $data['Result'] ?? null,
            notes: $data['Notes'] ?? null,
            failurePoints: $data['FailurePoints'] ?? [],
            testReadings: isset($data['TestReadings']) ? array_map(
                fn (array $tr) => TestReading::fromArray($tr),
                $data['TestReadings']
            ) : [],
        );
    }
}
