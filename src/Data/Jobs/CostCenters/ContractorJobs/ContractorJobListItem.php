<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ContractorJobs;

use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobContractor;
use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobCreatedBy;
use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobTotal;

final readonly class ContractorJobListItem
{
    public function __construct(
        public int $id,
        public string $projectType,
        public ?ContractorJobContractor $contractor,
        public ?ContractorJobCreatedBy $createdBy,
        public ?ContractorJobTotal $total,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            projectType: $data['ProjectType'] ?? '',
            contractor: ! empty($data['Contractor']) ? ContractorJobContractor::fromArray($data['Contractor']) : null,
            createdBy: ! empty($data['CreatedBy']) ? ContractorJobCreatedBy::fromArray($data['CreatedBy']) : null,
            total: ! empty($data['Total']) ? ContractorJobTotal::fromArray($data['Total']) : null,
        );
    }
}
