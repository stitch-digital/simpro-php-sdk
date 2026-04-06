<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

final readonly class ContractorInvoiceRetention
{
    public function __construct(
        public ?int $contractorJob = null,
        public ?ContractorInvoiceTotal $lineTotal = null,
        public ?TaxCodeReference $taxCode = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            contractorJob: isset($data['ContractorJob']) ? (int) $data['ContractorJob'] : null,
            lineTotal: ! empty($data['LineTotal']) ? ContractorInvoiceTotal::fromArray($data['LineTotal']) : null,
            taxCode: ! empty($data['TaxCode']) ? TaxCodeReference::fromArray($data['TaxCode']) : null,
        );
    }
}
