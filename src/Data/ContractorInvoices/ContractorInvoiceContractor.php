<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

final readonly class ContractorInvoiceContractor
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?ContractorInvoiceContractorAddress $address = null,
        public ?ContractorInvoiceContractorContact $primaryContact = null,
        public ?string $contactName = null,
        public ?ContractorInvoiceContractorBanking $banking = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? null,
            address: ! empty($data['Address']) ? ContractorInvoiceContractorAddress::fromArray($data['Address']) : null,
            primaryContact: ! empty($data['PrimaryContact']) ? ContractorInvoiceContractorContact::fromArray($data['PrimaryContact']) : null,
            contactName: $data['ContactName'] ?? null,
            banking: ! empty($data['Banking']) ? ContractorInvoiceContractorBanking::fromArray($data['Banking']) : null,
        );
    }
}
