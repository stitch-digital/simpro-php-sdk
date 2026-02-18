<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices\CreditNotes;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Invoices\InvoiceCostCenter;
use Simpro\PhpSdk\Simpro\Data\Invoices\InvoicePeriod;
use Simpro\PhpSdk\Simpro\Data\Invoices\InvoiceStatus;

final readonly class CreditNote
{
    /**
     * @param  array<CreditNoteJob>  $jobs
     * @param  array<InvoiceCostCenter>  $costCenters
     * @param  array<array<string, mixed>>  $customFields
     */
    public function __construct(
        public int $id,
        public ?string $type = null,
        public ?CreditNoteCustomer $customer = null,
        public array $jobs = [],
        public array $costCenters = [],
        public ?DateTimeImmutable $dateIssued = null,
        public ?InvoicePeriod $period = null,
        public ?string $stage = null,
        public ?bool $perItem = null,
        public ?string $orderNo = null,
        public ?Reference $incomeAccount = null,
        public ?InvoiceStatus $status = null,
        public ?bool $autoAdjustStatus = null,
        public ?string $description = null,
        public ?string $notes = null,
        public ?CreditNoteTotal $total = null,
        public ?DateTimeImmutable $dateModified = null,
        public ?DateTimeImmutable $dateCreated = null,
        public array $customFields = [],
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
            id: $data['ID'],
            type: $data['Type'] ?? null,
            customer: ! empty($data['Customer']) ? CreditNoteCustomer::fromArray($data['Customer']) : null,
            jobs: isset($data['Jobs']) ? array_map(
                fn (array $job) => CreditNoteJob::fromArray($job),
                $data['Jobs']
            ) : [],
            costCenters: isset($data['CostCenters']) ? array_map(
                fn (array $item) => InvoiceCostCenter::fromArray($item),
                $data['CostCenters']
            ) : [],
            dateIssued: ! empty($data['DateIssued']) ? new DateTimeImmutable($data['DateIssued']) : null,
            period: ! empty($data['Period']) ? InvoicePeriod::fromArray($data['Period']) : null,
            stage: $data['Stage'] ?? null,
            perItem: $data['PerItem'] ?? null,
            orderNo: $data['OrderNo'] ?? null,
            incomeAccount: ! empty($data['IncomeAccount']) ? Reference::fromArray($data['IncomeAccount']) : null,
            status: ! empty($data['Status']) ? InvoiceStatus::fromArray($data['Status']) : null,
            autoAdjustStatus: $data['AutoAdjustStatus'] ?? null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            total: ! empty($data['Total']) ? CreditNoteTotal::fromArray($data['Total']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            dateCreated: ! empty($data['DateCreated']) ? new DateTimeImmutable($data['DateCreated']) : null,
            customFields: $data['CustomFields'] ?? [],
        );
    }
}
