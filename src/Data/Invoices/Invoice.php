<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class Invoice
{
    /**
     * @param  array<InvoiceListJob>  $jobs
     * @param  array<InvoiceRetainage>  $retainage
     * @param  array<array<string, mixed>>  $customFields
     * @param  array<InvoiceCostCenter>  $costCenters
     */
    public function __construct(
        public int $id,
        public ?string $internalId = null,
        public ?string $type = null,
        public ?InvoiceCustomer $customer = null,
        public array $jobs = [],
        public ?InvoiceRecurringInvoice $recurringInvoice = null,
        public ?DateTimeImmutable $dateIssued = null,
        public ?InvoicePeriod $period = null,
        public ?int $paymentTermId = null,
        public ?InvoicePaymentTerms $paymentTerms = null,
        public ?int $progressClaimNumber = null,
        public ?bool $isFinalClaim = null,
        public ?string $stage = null,
        public ?bool $perItem = null,
        public ?string $orderNo = null,
        public ?bool $latePaymentFee = null,
        public ?float $cisDeductionRate = null,
        public ?float $exchangeRate = null,
        public ?Reference $accountingCategory = null,
        public ?InvoiceStatus $status = null,
        public ?bool $autoAdjustStatus = null,
        public ?string $description = null,
        public ?string $notes = null,
        public ?InvoiceTotal $total = null,
        public ?bool $isRetainage = null,
        public array $retainage = [],
        public ?string $retainageRebate = null,
        public ?bool $isPaid = null,
        public ?DateTimeImmutable $datePaid = null,
        public ?string $currency = null,
        public ?DateTimeImmutable $dateCreated = null,
        public ?DateTimeImmutable $dateModified = null,
        public array $customFields = [],
        public array $costCenters = [],
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
            internalId: $data['InternalID'] ?? null,
            type: $data['Type'] ?? null,
            customer: ! empty($data['Customer']) ? InvoiceCustomer::fromArray($data['Customer']) : null,
            jobs: isset($data['Jobs']) ? array_map(
                fn (array $job) => InvoiceListJob::fromArray($job),
                $data['Jobs']
            ) : [],
            recurringInvoice: ! empty($data['RecurringInvoice']) ? InvoiceRecurringInvoice::fromArray($data['RecurringInvoice']) : null,
            dateIssued: ! empty($data['DateIssued']) ? new DateTimeImmutable($data['DateIssued']) : null,
            period: ! empty($data['Period']) ? InvoicePeriod::fromArray($data['Period']) : null,
            paymentTermId: isset($data['PaymentTermID']) ? (int) $data['PaymentTermID'] : null,
            paymentTerms: ! empty($data['PaymentTerms']) ? InvoicePaymentTerms::fromArray($data['PaymentTerms']) : null,
            progressClaimNumber: isset($data['ProgressClaimNumber']) ? (int) $data['ProgressClaimNumber'] : null,
            isFinalClaim: $data['IsFinalClaim'] ?? null,
            stage: $data['Stage'] ?? null,
            perItem: $data['PerItem'] ?? null,
            orderNo: $data['OrderNo'] ?? null,
            latePaymentFee: $data['LatePaymentFee'] ?? null,
            cisDeductionRate: isset($data['CISDeductionRate']) ? (float) $data['CISDeductionRate'] : null,
            exchangeRate: isset($data['ExchangeRate']) ? (float) $data['ExchangeRate'] : null,
            accountingCategory: ! empty($data['AccountingCategory']) ? Reference::fromArray($data['AccountingCategory']) : null,
            status: ! empty($data['Status']) ? InvoiceStatus::fromArray($data['Status']) : null,
            autoAdjustStatus: $data['AutoAdjustStatus'] ?? null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            total: ! empty($data['Total']) ? InvoiceTotal::fromArray($data['Total']) : null,
            isRetainage: $data['IsRetainage'] ?? null,
            retainage: isset($data['Retainage']) ? array_map(
                fn (array $item) => InvoiceRetainage::fromArray($item),
                $data['Retainage']
            ) : [],
            retainageRebate: $data['RetainageRebate'] ?? null,
            isPaid: $data['IsPaid'] ?? null,
            datePaid: ! empty($data['DatePaid']) ? new DateTimeImmutable($data['DatePaid']) : null,
            currency: $data['Currency'] ?? null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            customFields: $data['CustomFields'] ?? [],
            costCenters: isset($data['CostCenters']) ? array_map(
                fn (array $item) => InvoiceCostCenter::fromArray($item),
                $data['CostCenters']
            ) : [],
        );
    }
}
