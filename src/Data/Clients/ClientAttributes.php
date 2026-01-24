<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Clients;

use DateTimeImmutable;

final readonly class ClientAttributes
{
    /**
     * @param  array<string, mixed>  $extraFields
     */
    public function __construct(
        public ?string $webUrl = null,
        public ?DateTimeImmutable $created = null,
        public ?DateTimeImmutable $updated = null,
        public ?string $ref = null,
        public ?string $name = null,
        public ?bool $isActive = null,
        public ?string $contactName = null,
        public ?string $contactOrganisation = null,
        public ?string $contactMobile = null,
        public ?string $contactPhoneBh = null,
        public ?string $contactPhoneAh = null,
        public ?string $contactEmail = null,
        public ?string $contactEmailCc = null,
        public ?string $contactAddress = null,
        public ?string $autocompLabel = null,
        public ?string $businessHours = null,
        public ?string $reportRequirements = null,
        public ?string $reportAttention = null,
        public ?string $reportEmailTo = null,
        public ?string $reportEmailCc = null,
        public ?bool $reportManual = null,
        public ?bool $reportMerge = null,
        public ?bool $reportWhitelabel = null,
        public ?string $reportOrganisation = null,
        public ?string $reportAddress = null,
        public ?string $billingOrganisation = null,
        public ?string $billingRequirements = null,
        public ?string $billingAttention = null,
        public ?string $billingEmailTo = null,
        public ?string $billingEmailCc = null,
        public ?bool $billingManual = null,
        public ?string $billingAddress = null,
        public ?bool $billingFixedprice = null,
        public ?string $quotingAttention = null,
        public ?string $quotingEmailTo = null,
        public ?string $quotingEmailCc = null,
        public ?bool $quotingAutoremindersEnabled = null,
        public ?string $quotingRequirements = null,
        public ?string $notes = null,
        public ?string $address = null,
        public ?float $materialMarkup = null,
        public array $extraFields = [],
        public ?string $primaryContactEmail = null,
        public ?Sector $sector = null,
        public ?int $propertyCount = null,
        public ?int $openFaultCount = null,
        public ?string $defectsCount = null,
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        $created = null;
        if (isset($data['created']) && is_string($data['created'])) {
            $created = DateTimeImmutable::createFromFormat(DATE_ATOM, $data['created']) ?: null;
            if ($created === null) {
                $created = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s\Z', $data['created']) ?: null;
            }
        }

        $updated = null;
        if (isset($data['updated']) && is_string($data['updated'])) {
            $updated = DateTimeImmutable::createFromFormat(DATE_ATOM, $data['updated']) ?: null;
            if ($updated === null) {
                $updated = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s\Z', $data['updated']) ?: null;
            }
        }

        $materialMarkup = null;
        if (isset($data['material_markup'])) {
            $materialMarkup = is_numeric($data['material_markup']) ? (float) $data['material_markup'] : null;
        }

        $sector = null;
        if (isset($data['sector']) && is_string($data['sector'])) {
            $sector = Sector::tryFrom($data['sector']);
        }

        $propertyCount = null;
        if (isset($data['property_count'])) {
            $propertyCount = is_numeric($data['property_count']) ? (int) $data['property_count'] : null;
        }

        $openFaultCount = null;
        if (isset($data['open_fault_count'])) {
            $openFaultCount = is_numeric($data['open_fault_count']) ? (int) $data['open_fault_count'] : null;
        }

        return new self(
            webUrl: $data['__web_url__'] ?? null,
            created: $created,
            updated: $updated,
            ref: $data['ref'] ?? null,
            name: $data['name'] ?? null,
            isActive: $data['is_active'] ?? null,
            contactName: $data['contact_name'] ?? null,
            contactOrganisation: $data['contact_organisation'] ?? null,
            contactMobile: $data['contact_mobile'] ?? null,
            contactPhoneBh: $data['contact_phone_bh'] ?? null,
            contactPhoneAh: $data['contact_phone_ah'] ?? null,
            contactEmail: $data['contact_email'] ?? null,
            contactEmailCc: $data['contact_email_cc'] ?? null,
            contactAddress: $data['contact_address'] ?? null,
            autocompLabel: $data['autocomp_label'] ?? null,
            businessHours: $data['business_hours'] ?? null,
            reportRequirements: $data['report_requirements'] ?? null,
            reportAttention: $data['report_attention'] ?? null,
            reportEmailTo: $data['report_email_to'] ?? null,
            reportEmailCc: $data['report_email_cc'] ?? null,
            reportManual: $data['report_manual'] ?? null,
            reportMerge: $data['report_merge'] ?? null,
            reportWhitelabel: $data['report_whitelabel'] ?? null,
            reportOrganisation: $data['report_organisation'] ?? null,
            reportAddress: $data['report_address'] ?? null,
            billingOrganisation: $data['billing_organisation'] ?? null,
            billingRequirements: $data['billing_requirements'] ?? null,
            billingAttention: $data['billing_attention'] ?? null,
            billingEmailTo: $data['billing_email_to'] ?? null,
            billingEmailCc: $data['billing_email_cc'] ?? null,
            billingManual: $data['billing_manual'] ?? null,
            billingAddress: $data['billing_address'] ?? null,
            billingFixedprice: $data['billing_fixedprice'] ?? null,
            quotingAttention: $data['quoting_attention'] ?? null,
            quotingEmailTo: $data['quoting_email_to'] ?? null,
            quotingEmailCc: $data['quoting_email_cc'] ?? null,
            quotingAutoremindersEnabled: $data['quoting_autoreminders_enabled'] ?? null,
            quotingRequirements: $data['quoting_requirements'] ?? null,
            notes: $data['notes'] ?? null,
            address: $data['address'] ?? null,
            materialMarkup: $materialMarkup,
            extraFields: $data['extra_fields'] ?? [],
            primaryContactEmail: $data['primary_contact__email'] ?? null,
            sector: $sector,
            propertyCount: $propertyCount,
            openFaultCount: $openFaultCount,
            defectsCount: $data['defects_count'] ?? null,
        );
    }
}
