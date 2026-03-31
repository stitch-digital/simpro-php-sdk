<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ContractorJobs;

final readonly class ContractorJobPrebuildItem
{
    public function __construct(
        public int $id,
        public ?int $prebuildId,
        public ?string $prebuildPartNo,
        public ?string $prebuildName,
        public ?float $priceLabor,
        public ?float $priceMaterial,
        public ?float $qtyAssigned,
        public ?float $qtyRemaining,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            prebuildId: isset($data['Prebuild']['ID']) ? (int) $data['Prebuild']['ID'] : null,
            prebuildPartNo: $data['Prebuild']['PartNo'] ?? null,
            prebuildName: $data['Prebuild']['Name'] ?? null,
            priceLabor: isset($data['Price']['Labor']) ? (float) $data['Price']['Labor'] : null,
            priceMaterial: isset($data['Price']['Material']) ? (float) $data['Price']['Material'] : null,
            qtyAssigned: isset($data['Qty']['Assigned']) ? (float) $data['Qty']['Assigned'] : null,
            qtyRemaining: isset($data['Qty']['Remaining']) ? (float) $data['Qty']['Remaining'] : null,
        );
    }
}
