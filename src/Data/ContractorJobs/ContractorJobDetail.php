<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorJobs;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class ContractorJobDetail
{
    /**
     * @param  array<mixed>  $customFields
     */
    public function __construct(
        public int $id,
        public string $projectType,
        public ?ContractorJobContractor $contractor,
        public ?ContractorJobCreatedBy $createdBy,
        public string $status,
        public string $description,
        public string $dateIssued,
        public string $dueDate,
        public bool $contractorSupplyMaterials,
        public float $materials,
        public string $currency,
        public float $exchangeRate,
        public ?float $labor,
        public ?ContractorJobTaxCode $taxCode,
        public ?ContractorJobRetention $retention,
        public ?ContractorJobTotal $total,
        public array $customFields,
        public ?DateTimeImmutable $dateModified,
        public string $href,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

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
            status: $data['Status'] ?? '',
            description: $data['Description'] ?? '',
            dateIssued: $data['DateIssued'] ?? '',
            dueDate: $data['DueDate'] ?? '',
            contractorSupplyMaterials: $data['ContractorSupplyMaterials'] ?? false,
            materials: (float) ($data['Materials'] ?? 0),
            currency: $data['Currency'] ?? '',
            exchangeRate: (float) ($data['ExchangeRate'] ?? 1),
            labor: isset($data['Labor']) ? (float) $data['Labor'] : null,
            taxCode: ! empty($data['TaxCode']) ? ContractorJobTaxCode::fromArray($data['TaxCode']) : null,
            retention: ! empty($data['Retention']) ? ContractorJobRetention::fromArray($data['Retention']) : null,
            total: ! empty($data['Total']) ? ContractorJobTotal::fromArray($data['Total']) : null,
            customFields: $data['CustomFields'] ?? [],
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            href: $data['_href'] ?? '',
        );
    }
}
