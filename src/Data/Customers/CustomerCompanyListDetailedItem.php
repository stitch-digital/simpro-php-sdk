<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Address;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * Detailed customer company list item with all available columns.
 *
 * This DTO is returned when using listCompaniesDetailed() to get
 * maximum data from a single list request.
 */
final readonly class CustomerCompanyListDetailedItem
{
    /**
     * @param  array<CustomerTag>|null  $tags
     * @param  array<CustomerSite>|null  $sites
     * @param  array<CustomerContract>|null  $contracts
     * @param  array<CustomerContact>|null  $contacts
     * @param  array<CustomerResponseTime>|null  $responseTimes
     * @param  array<StaffReference>|null  $preferredTechs
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public ?string $companyName,
        public ?string $givenName,
        public ?string $familyName,
        public ?string $phone,
        public ?string $altPhone,
        public ?string $fax,
        public ?string $email,
        public ?string $website,
        public ?string $ein,
        public ?string $companyNumber,
        public ?string $href,
        public ?Address $address,
        public ?Address $billingAddress,
        public ?string $customerType,
        public ?array $tags,
        public ?float $amountOwing,
        public ?CustomerRates $rates,
        public ?CustomerProfileDetails $profile,
        public ?CustomerBankingDetails $banking,
        public ?bool $archived,
        public ?bool $doNotCall,
        public ?array $sites,
        public ?array $contracts,
        public ?array $contacts,
        public ?array $responseTimes,
        public ?array $preferredTechs,
        public ?array $customFields,
        public ?DateTimeImmutable $dateModified,
        public ?DateTimeImmutable $dateCreated,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            companyName: $data['CompanyName'] ?? null,
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            phone: $data['Phone'] ?? null,
            altPhone: $data['AltPhone'] ?? null,
            fax: $data['Fax'] ?? null,
            email: $data['Email'] ?? null,
            website: $data['Website'] ?? null,
            ein: $data['EIN'] ?? null,
            companyNumber: $data['CompanyNumber'] ?? null,
            href: $data['_href'] ?? null,
            address: isset($data['Address']) && is_array($data['Address']) ? Address::fromArray($data['Address']) : null,
            billingAddress: isset($data['BillingAddress']) && is_array($data['BillingAddress']) ? Address::fromArray($data['BillingAddress']) : null,
            customerType: $data['CustomerType'] ?? null,
            tags: isset($data['Tags']) && is_array($data['Tags']) ? array_map(
                fn (array $item) => CustomerTag::fromArray($item),
                $data['Tags']
            ) : null,
            amountOwing: isset($data['AmountOwing']) ? (float) $data['AmountOwing'] : null,
            rates: isset($data['Rates']) && is_array($data['Rates']) ? CustomerRates::fromArray($data['Rates']) : null,
            profile: isset($data['Profile']) && is_array($data['Profile']) ? CustomerProfileDetails::fromArray($data['Profile']) : null,
            banking: isset($data['Banking']) && is_array($data['Banking']) ? CustomerBankingDetails::fromArray($data['Banking']) : null,
            archived: $data['Archived'] ?? null,
            doNotCall: $data['DoNotCall'] ?? null,
            sites: isset($data['Sites']) && is_array($data['Sites']) ? array_map(
                fn (array $item) => CustomerSite::fromArray($item),
                $data['Sites']
            ) : null,
            contracts: isset($data['Contracts']) && is_array($data['Contracts']) ? array_map(
                fn (array $item) => CustomerContract::fromArray($item),
                $data['Contracts']
            ) : null,
            contacts: isset($data['Contacts']) && is_array($data['Contacts']) ? array_map(
                fn (array $item) => CustomerContact::fromArray($item),
                $data['Contacts']
            ) : null,
            responseTimes: isset($data['ResponseTimes']) && is_array($data['ResponseTimes']) ? array_map(
                fn (array $item) => CustomerResponseTime::fromArray($item),
                $data['ResponseTimes']
            ) : null,
            preferredTechs: isset($data['PreferredTechs']) && is_array($data['PreferredTechs']) ? array_map(
                fn (array $item) => StaffReference::fromArray($item),
                $data['PreferredTechs']
            ) : null,
            customFields: isset($data['CustomFields']) && is_array($data['CustomFields']) ? array_map(
                fn (array $item) => CustomField::fromArray($item),
                $data['CustomFields']
            ) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
        );
    }

    /**
     * Get the display name for the customer.
     */
    public function displayName(): string
    {
        return $this->companyName ?? '';
    }

    /**
     * Check if this customer is archived.
     */
    public function isArchived(): bool
    {
        return $this->archived === true;
    }

    /**
     * Check if this customer has an outstanding balance.
     */
    public function hasAmountOwing(): bool
    {
        return $this->amountOwing !== null && $this->amountOwing > 0;
    }
}
