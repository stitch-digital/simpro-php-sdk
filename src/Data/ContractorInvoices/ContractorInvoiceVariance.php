<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class ContractorInvoiceVariance
{
    public function __construct(
        public ?int $id = null,
        public ?Reference $costCenter = null,
        public ?ContractorInvoiceTotal $lineTotal = null,
        public ?TaxCodeReference $taxCode = null,
        public ?Reference $businessGroup = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['ID']) ? (int) $data['ID'] : null,
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            lineTotal: ! empty($data['LineTotal']) ? ContractorInvoiceTotal::fromArray($data['LineTotal']) : null,
            taxCode: ! empty($data['TaxCode']) ? TaxCodeReference::fromArray($data['TaxCode']) : null,
            businessGroup: ! empty($data['BusinessGroup']) ? Reference::fromArray($data['BusinessGroup']) : null,
        );
    }
}
