<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * DTO for a single company customer (detailed view).
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/companies/{customerID}
 */
final readonly class Customer
{
    /**
     * @param  array<StaffReference>|null  $preferredTechs
     * @param  array<Reference>|null  $tags
     * @param  array<Reference>|null  $sites
     * @param  array<CustomerContractSummary>|null  $contracts
     * @param  array<CustomerContactSummary>|null  $contacts
     * @param  array<Reference>|null  $responseTimes
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public string $companyName,
        public ?array $preferredTechs,
        public string $phone,
        public bool $doNotCall,
        public string $altPhone,
        public ?CustomerAddress $address,
        public ?CustomerAddress $billingAddress,
        public string $customerType,
        public ?array $tags,
        public float $amountOwing,
        public ?CustomerRates $rates,
        public ?CustomerProfileDetails $profile,
        public ?CustomerBankingDetails $banking,
        public bool $archived,
        public ?array $sites,
        public ?array $contracts,
        public ?array $contacts,
        public ?array $responseTimes,
        public ?array $customFields,
        public string $email,
        public ?DateTimeImmutable $dateModified,
        public ?DateTimeImmutable $dateCreated,
        public string $ein,
        public string $website,
        public string $fax,
        public string $companyNumber,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            companyName: $data['CompanyName'] ?? '',
            preferredTechs: isset($data['PreferredTechs']) ? array_map(fn (array $item) => StaffReference::fromArray($item), $data['PreferredTechs']) : null,
            phone: $data['Phone'] ?? '',
            doNotCall: $data['DoNotCall'] ?? false,
            altPhone: $data['AltPhone'] ?? '',
            address: ! empty($data['Address']) ? CustomerAddress::fromArray($data['Address']) : null,
            billingAddress: ! empty($data['BillingAddress']) ? CustomerAddress::fromArray($data['BillingAddress']) : null,
            customerType: $data['CustomerType'] ?? 'Customer',
            tags: isset($data['Tags']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['Tags']) : null,
            amountOwing: isset($data['AmountOwing']) ? (float) $data['AmountOwing'] : 0.0,
            rates: ! empty($data['Rates']) ? CustomerRates::fromArray($data['Rates']) : null,
            profile: ! empty($data['Profile']) ? CustomerProfileDetails::fromArray($data['Profile']) : null,
            banking: ! empty($data['Banking']) ? CustomerBankingDetails::fromArray($data['Banking']) : null,
            archived: $data['Archived'] ?? false,
            sites: isset($data['Sites']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['Sites']) : null,
            contracts: isset($data['Contracts']) ? array_map(fn (array $item) => CustomerContractSummary::fromArray($item), $data['Contracts']) : null,
            contacts: isset($data['Contacts']) ? array_map(fn (array $item) => CustomerContactSummary::fromArray($item), $data['Contacts']) : null,
            responseTimes: isset($data['ResponseTimes']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['ResponseTimes']) : null,
            customFields: isset($data['CustomFields']) ? array_map(fn (array $item) => CustomField::fromArray($item), $data['CustomFields']) : null,
            email: $data['Email'] ?? '',
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            ein: $data['EIN'] ?? '',
            website: $data['Website'] ?? '',
            fax: $data['Fax'] ?? '',
            companyNumber: $data['CompanyNumber'] ?? '',
        );
    }
}
