<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Data\Common\TaxCode;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\PricingTier;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobTotal;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobTotals;
use Simpro\PhpSdk\Simpro\Data\Setup\FitTime;

/**
 * A recurring invoice template.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/recurringInvoices/{recurringInvoiceID}
 */
final readonly class RecurringInvoice
{
    /**
     * @param  array<RecurringInvoiceContact>|null  $additionalContacts
     * @param  array<Reference>|null  $tags
     * @param  array<StaffReference>|null  $technicians
     * @param  array<CustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public ?RecurringInvoiceType $type,
        public ?RecurringInvoiceCustomer $customer,
        public ?RecurringInvoiceContract $customerContract,
        public ?RecurringInvoiceContact $customerContact,
        public ?array $additionalContacts,
        public ?Reference $site,
        public ?RecurringInvoiceContact $siteContact,
        public ?string $requestNo,
        public ?string $name,
        public ?string $orderNo,
        public ?string $description,
        public ?string $notes,
        public ?DateTimeImmutable $recurringStartDate,
        public ?DateTimeImmutable $nextRecurringDate,
        public ?int $recurringQuantity,
        public ?InvoiceDatePeriod $invoiceDatePeriod,
        public ?RecurringFrequency $recurringFrequency,
        public ?array $tags,
        public ?int $paymentTermId,
        public ?RecurringInvoicePaymentTerms $paymentTerms,
        public ?StaffReference $salesperson,
        public ?StaffReference $projectManager,
        public ?array $technicians,
        public ?PricingTier $pricingTier,
        public ?float $laborOverhead,
        public ?Reference $laborType,
        public ?FitTime $laborFitTime,
        public ?TaxCode $itemTaxCode,
        public ?TaxCode $labourTaxCode,
        public ?float $materialMarkup,
        public ?float $inflationRate,
        public ?DateTimeImmutable $inflationAnniversaryDate,
        public ?DateTimeImmutable $dateModified,
        public ?JobTotal $total,
        public ?JobTotals $totals,
        public ?array $customFields,
        public ?bool $archived,
        public ?bool $removed,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            type: isset($data['Type']) ? RecurringInvoiceType::tryFrom($data['Type']) : null,
            customer: ! empty($data['Customer']) ? RecurringInvoiceCustomer::fromArray($data['Customer']) : null,
            customerContract: ! empty($data['CustomerContract']) ? RecurringInvoiceContract::fromArray($data['CustomerContract']) : null,
            customerContact: ! empty($data['CustomerContact']) ? RecurringInvoiceContact::fromArray($data['CustomerContact']) : null,
            additionalContacts: isset($data['AdditionalContacts']) ? array_map(
                fn (array $item) => RecurringInvoiceContact::fromArray($item),
                $data['AdditionalContacts']
            ) : null,
            site: ! empty($data['Site']) ? Reference::fromArray($data['Site']) : null,
            siteContact: ! empty($data['SiteContact']) ? RecurringInvoiceContact::fromArray($data['SiteContact']) : null,
            requestNo: $data['RequestNo'] ?? null,
            name: $data['Name'] ?? null,
            orderNo: $data['OrderNo'] ?? null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            recurringStartDate: ! empty($data['RecurringStartDate']) ? new DateTimeImmutable($data['RecurringStartDate']) : null,
            nextRecurringDate: ! empty($data['NextRecurringDate']) ? new DateTimeImmutable($data['NextRecurringDate']) : null,
            recurringQuantity: isset($data['RecurringQuantity']) ? (int) $data['RecurringQuantity'] : null,
            invoiceDatePeriod: isset($data['InvoiceDatePeriod']) ? InvoiceDatePeriod::tryFrom($data['InvoiceDatePeriod']) : null,
            recurringFrequency: isset($data['RecurringFrequency']) ? RecurringFrequency::tryFrom($data['RecurringFrequency']) : null,
            tags: isset($data['Tags']) ? array_map(
                fn (array $item) => Reference::fromArray($item),
                $data['Tags']
            ) : null,
            paymentTermId: isset($data['PaymentTermID']) ? (int) $data['PaymentTermID'] : null,
            paymentTerms: ! empty($data['PaymentTerms']) ? RecurringInvoicePaymentTerms::fromArray($data['PaymentTerms']) : null,
            salesperson: ! empty($data['Salesperson']) ? StaffReference::fromArray($data['Salesperson']) : null,
            projectManager: ! empty($data['ProjectManager']) ? StaffReference::fromArray($data['ProjectManager']) : null,
            technicians: isset($data['Technicians']) ? array_map(
                fn (array $item) => StaffReference::fromArray($item),
                $data['Technicians']
            ) : null,
            pricingTier: ! empty($data['PricingTier']) ? PricingTier::fromArray($data['PricingTier']) : null,
            laborOverhead: isset($data['LaborOverhead']) ? (float) $data['LaborOverhead'] : null,
            laborType: ! empty($data['LaborType']) ? Reference::fromArray($data['LaborType']) : null,
            laborFitTime: ! empty($data['LaborFitTime']) ? FitTime::fromArray($data['LaborFitTime']) : null,
            itemTaxCode: ! empty($data['ItemTaxCode']) ? TaxCode::fromArray($data['ItemTaxCode']) : null,
            labourTaxCode: ! empty($data['LabourTaxCode']) ? TaxCode::fromArray($data['LabourTaxCode']) : null,
            materialMarkup: isset($data['MaterialMarkup']) ? (float) $data['MaterialMarkup'] : null,
            inflationRate: isset($data['InflationRate']) ? (float) $data['InflationRate'] : null,
            inflationAnniversaryDate: ! empty($data['InflationAnniversaryDate']) ? new DateTimeImmutable($data['InflationAnniversaryDate']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? JobTotal::fromArray($data['Total']) : null,
            totals: ! empty($data['Totals']) ? JobTotals::fromArray($data['Totals']) : null,
            customFields: isset($data['CustomFields']) ? array_map(
                fn (array $item) => CustomField::fromArray($item),
                $data['CustomFields']
            ) : null,
            archived: isset($data['Archived']) ? (bool) $data['Archived'] : null,
            removed: isset($data['Removed']) ? (bool) $data['Removed'] : null,
        );
    }
}
