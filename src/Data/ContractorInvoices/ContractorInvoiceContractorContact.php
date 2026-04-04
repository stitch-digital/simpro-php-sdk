<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

final readonly class ContractorInvoiceContractorContact
{
    public function __construct(
        public ?string $email = null,
        public ?string $secondaryEmail = null,
        public ?string $workPhone = null,
        public ?string $extension = null,
        public ?string $cellPhone = null,
        public ?string $fax = null,
        public ?string $preferredNotificationMethod = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['Email'] ?? null,
            secondaryEmail: $data['SecondaryEmail'] ?? null,
            workPhone: $data['WorkPhone'] ?? null,
            extension: $data['Extension'] ?? null,
            cellPhone: $data['CellPhone'] ?? null,
            fax: $data['Fax'] ?? null,
            preferredNotificationMethod: $data['PreferredNotificationMethod'] ?? null,
        );
    }
}
