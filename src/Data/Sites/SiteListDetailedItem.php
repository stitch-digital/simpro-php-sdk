<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Sites;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\Address;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class SiteListDetailedItem
{
    /**
     * @param  array<SiteCustomerReference>|null  $customers
     * @param  array<CustomField>|null  $customFields
     * @param  array<mixed>|null  $preferredTechs
     * @param  array<mixed>|null  $preferredTechnicians
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?Address $address = null,
        public ?Address $billingAddress = null,
        public ?string $billingContact = null,
        public ?SitePrimaryContact $primaryContact = null,
        public ?string $publicNotes = null,
        public ?string $privateNotes = null,
        public ?Reference $zone = null,
        public ?array $preferredTechs = null,
        public ?array $preferredTechnicians = null,
        public ?DateTimeImmutable $dateModified = null,
        public ?array $customers = null,
        public ?array $customFields = null,
        public ?SiteRates $rates = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            address: isset($data['Address']) && is_array($data['Address'])
                ? Address::fromArray($data['Address'])
                : null,
            billingAddress: isset($data['BillingAddress']) && is_array($data['BillingAddress'])
                ? Address::fromArray($data['BillingAddress'])
                : null,
            billingContact: is_string($data['BillingContact'] ?? null) ? $data['BillingContact'] : null,
            primaryContact: isset($data['PrimaryContact']) && is_array($data['PrimaryContact'])
                ? SitePrimaryContact::fromArray($data['PrimaryContact'])
                : null,
            publicNotes: $data['PublicNotes'] ?? null,
            privateNotes: $data['PrivateNotes'] ?? null,
            zone: isset($data['Zone']) && is_array($data['Zone'])
                ? Reference::fromArray($data['Zone'])
                : null,
            preferredTechs: $data['PreferredTechs'] ?? null,
            preferredTechnicians: $data['PreferredTechnicians'] ?? null,
            dateModified: isset($data['DateModified'])
                ? new DateTimeImmutable($data['DateModified'])
                : null,
            customers: isset($data['Customers']) && is_array($data['Customers'])
                ? array_map(fn (array $item) => SiteCustomerReference::fromArray($item), $data['Customers'])
                : null,
            customFields: isset($data['CustomFields']) && is_array($data['CustomFields'])
                ? array_map(fn (array $item) => CustomField::fromArray($item), $data['CustomFields'])
                : null,
            rates: isset($data['Rates']) && is_array($data['Rates'])
                ? SiteRates::fromArray($data['Rates'])
                : null,
        );
    }
}
