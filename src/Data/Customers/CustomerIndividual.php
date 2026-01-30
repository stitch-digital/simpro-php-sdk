<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * DTO for a single individual customer (detailed view).
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/individuals/{customerID}
 */
final readonly class CustomerIndividual
{
    /**
     * @param  array<StaffReference>|null  $preferredTechs
     * @param  array<Reference>|null  $tags
     * @param  array<Reference>|null  $sites
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $givenName,
        public string $familyName,
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
        public ?array $customFields,
        public string $email,
        public ?DateTimeImmutable $dateModified,
        public ?DateTimeImmutable $dateCreated,
        public string $cellPhone,
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
            title: $data['Title'] ?? '',
            givenName: $data['GivenName'] ?? '',
            familyName: $data['FamilyName'] ?? '',
            preferredTechs: isset($data['PreferredTechs']) ? array_map(fn (array $item) => StaffReference::fromArray($item), $data['PreferredTechs']) : null,
            phone: $data['Phone'] ?? '',
            doNotCall: $data['DoNotCall'] ?? false,
            altPhone: $data['AltPhone'] ?? '',
            address: isset($data['Address']) ? CustomerAddress::fromArray($data['Address']) : null,
            billingAddress: isset($data['BillingAddress']) ? CustomerAddress::fromArray($data['BillingAddress']) : null,
            customerType: $data['CustomerType'] ?? 'Customer',
            tags: isset($data['Tags']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['Tags']) : null,
            amountOwing: isset($data['AmountOwing']) ? (float) $data['AmountOwing'] : 0.0,
            rates: isset($data['Rates']) ? CustomerRates::fromArray($data['Rates']) : null,
            profile: isset($data['Profile']) ? CustomerProfileDetails::fromArray($data['Profile']) : null,
            banking: isset($data['Banking']) ? CustomerBankingDetails::fromArray($data['Banking']) : null,
            archived: $data['Archived'] ?? false,
            sites: isset($data['Sites']) ? array_map(fn (array $item) => Reference::fromArray($item), $data['Sites']) : null,
            customFields: isset($data['CustomFields']) ? array_map(fn (array $item) => CustomField::fromArray($item), $data['CustomFields']) : null,
            email: $data['Email'] ?? '',
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            dateCreated: isset($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            cellPhone: $data['CellPhone'] ?? '',
        );
    }
}
