<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Address;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;

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
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public ?string $companyName,
        public ?string $phone,
        public ?string $email,
        public ?string $href,
        public ?Address $address,
        public ?Address $billingAddress,
        public ?CustomerType $customerType,
        public ?array $tags,
        public ?float $amountOwing,
        public ?CustomerProfile $profile,
        public ?CustomerBanking $banking,
        public ?bool $archived,
        public ?array $sites,
        public ?array $contracts,
        public ?array $contacts,
        public ?array $responseTimes,
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
            phone: $data['Phone'] ?? null,
            email: $data['Email'] ?? null,
            href: $data['_href'] ?? null,
            address: isset($data['Address']) && is_array($data['Address']) ? Address::fromArray($data['Address']) : null,
            billingAddress: isset($data['BillingAddress']) && is_array($data['BillingAddress']) ? Address::fromArray($data['BillingAddress']) : null,
            customerType: isset($data['CustomerType']) && is_array($data['CustomerType']) ? CustomerType::fromArray($data['CustomerType']) : null,
            tags: isset($data['Tags']) && is_array($data['Tags']) ? array_map(
                fn (array $item) => CustomerTag::fromArray($item),
                $data['Tags']
            ) : null,
            amountOwing: isset($data['AmountOwing']) ? (float) $data['AmountOwing'] : null,
            profile: isset($data['Profile']) && is_array($data['Profile']) ? CustomerProfile::fromArray($data['Profile']) : null,
            banking: isset($data['Banking']) && is_array($data['Banking']) ? CustomerBanking::fromArray($data['Banking']) : null,
            archived: $data['Archived'] ?? null,
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
