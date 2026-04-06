<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class ContractorInvoiceCostCenter
{
    public function __construct(
        public ?int $contractorJob = null,
        public ?Reference $costCenter = null,
        public ?string $description = null,
        public ?string $jobNo = null,
        public ?ContractorInvoiceTotal $material = null,
        public ?ContractorInvoiceTotal $labour = null,
        public ?ContractorInvoiceTotal $lineTotal = null,
        public ?ContractorInvoiceTotal $total = null,
        public ?ContractorInvoiceTotal $invoiced = null,
        public bool $complete = false,
        public ?TaxCodeReference $taxCode = null,
        public ?Reference $businessGroup = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            contractorJob: isset($data['ContractorJob']) ? (int) $data['ContractorJob'] : null,
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            description: $data['Description'] ?? null,
            jobNo: $data['JobNo'] ?? null,
            material: ! empty($data['Material']) ? ContractorInvoiceTotal::fromArray($data['Material']) : null,
            labour: ! empty($data['Labour']) ? ContractorInvoiceTotal::fromArray($data['Labour']) : null,
            lineTotal: ! empty($data['LineTotal']) ? ContractorInvoiceTotal::fromArray($data['LineTotal']) : null,
            total: ! empty($data['Total']) ? ContractorInvoiceTotal::fromArray($data['Total']) : null,
            invoiced: ! empty($data['Invoiced']) ? ContractorInvoiceTotal::fromArray($data['Invoiced']) : null,
            complete: (bool) ($data['Complete'] ?? false),
            taxCode: ! empty($data['TaxCode']) ? TaxCodeReference::fromArray($data['TaxCode']) : null,
            businessGroup: ! empty($data['BusinessGroup']) ? Reference::fromArray($data['BusinessGroup']) : null,
        );
    }
}
