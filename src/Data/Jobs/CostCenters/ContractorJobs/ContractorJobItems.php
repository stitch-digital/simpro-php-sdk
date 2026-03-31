<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ContractorJobs;

final readonly class ContractorJobItems
{
    /**
     * @param  array<ContractorJobCatalogItem>  $catalogs
     * @param  array<ContractorJobPrebuildItem>  $prebuilds
     */
    public function __construct(
        public array $catalogs,
        public array $prebuilds,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            catalogs: isset($data['Catalogs']) ? array_map(
                fn (array $item) => ContractorJobCatalogItem::fromArray($item),
                $data['Catalogs']
            ) : [],
            prebuilds: isset($data['Prebuilds']) ? array_map(
                fn (array $item) => ContractorJobPrebuildItem::fromArray($item),
                $data['Prebuilds']
            ) : [],
        );
    }
}
