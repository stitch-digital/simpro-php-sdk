<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class ContractorInvoice
{
    /**
     * @param  array<int>  $contractorJobs
     * @param  array<ContractorInvoiceCostCenter>  $costCenters
     * @param  array<ContractorInvoiceRetention>  $retentions
     * @param  array<ContractorInvoiceVariance>  $variances
     * @param  array<array<string, mixed>>  $customFields
     */
    public function __construct(
        public int $id,
        public array $contractorJobs = [],
        public ?ContractorInvoiceContractor $contractor = null,
        public ?string $invoiceNo = null,
        public ?DateTimeImmutable $dateIssued = null,
        public ?DateTimeImmutable $dueDate = null,
        public ?DateTimeImmutable $datePaid = null,
        public ?string $currency = null,
        public ?float $exchangeRate = null,
        public ?Reference $category = null,
        public ?string $notes = null,
        public array $costCenters = [],
        public array $retentions = [],
        public array $variances = [],
        public ?ContractorInvoiceTotal $total = null,
        public array $customFields = [],
        public ?DateTimeImmutable $dateModified = null,
        public ?float $cisDeduction = null,
        public ?float $rctDeduction = null,
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
            contractorJobs: array_map('intval', $data['ContractorJobs'] ?? []),
            contractor: ! empty($data['Contractor']) ? ContractorInvoiceContractor::fromArray($data['Contractor']) : null,
            invoiceNo: $data['InvoiceNo'] ?? null,
            dateIssued: ! empty($data['DateIssued']) ? new DateTimeImmutable($data['DateIssued']) : null,
            dueDate: ! empty($data['DueDate']) ? new DateTimeImmutable($data['DueDate']) : null,
            datePaid: ! empty($data['DatePaid']) ? new DateTimeImmutable($data['DatePaid']) : null,
            currency: $data['Currency'] ?? null,
            exchangeRate: isset($data['ExchangeRate']) ? (float) $data['ExchangeRate'] : null,
            category: ! empty($data['Category']) ? Reference::fromArray($data['Category']) : null,
            notes: $data['Notes'] ?? null,
            costCenters: isset($data['CostCenters']) ? array_map(
                fn (array $item) => ContractorInvoiceCostCenter::fromArray($item),
                $data['CostCenters']
            ) : [],
            retentions: isset($data['Retentions']) ? array_map(
                fn (array $item) => ContractorInvoiceRetention::fromArray($item),
                $data['Retentions']
            ) : [],
            variances: isset($data['Variances']) ? array_map(
                fn (array $item) => ContractorInvoiceVariance::fromArray($item),
                $data['Variances']
            ) : [],
            total: ! empty($data['Total']) ? ContractorInvoiceTotal::fromArray($data['Total']) : null,
            customFields: $data['CustomFields'] ?? [],
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            cisDeduction: isset($data['CISDeduction']) ? (float) $data['CISDeduction'] : null,
            rctDeduction: isset($data['RCTDeduction']) ? (float) $data['RCTDeduction'] : null,
        );
    }
}
