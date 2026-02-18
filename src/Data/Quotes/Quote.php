<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class Quote
{
    /**
     * @param  array<QuoteCustomer>|null  $additionalCustomers
     * @param  array<QuoteContact>|null  $additionalContacts
     * @param  array<StaffReference>|null  $technicians
     * @param  array<Reference>|null  $tags
     * @param  array<mixed>|null  $customFields
     */
    public function __construct(
        public int $id,
        public ?string $type,
        public ?string $name,
        public ?QuoteSite $site,
        public ?QuoteCustomer $customer,
        public ?array $additionalCustomers,
        public ?QuoteContact $customerContact,
        public ?array $additionalContacts,
        public ?QuoteContact $siteContact,
        public ?QuoteConvertedFromLead $convertedFromLead,
        public ?StaffReference $salesperson,
        public ?StaffReference $projectManager,
        public ?array $technicians,
        public ?StaffReference $technician,
        public QuoteStatus|string|null $status,
        public ?string $stage,
        public ?string $orderNo,
        public ?string $requestNo,
        public ?string $description,
        public ?string $notes,
        public ?DateTimeImmutable $dateIssued,
        public ?DateTimeImmutable $dueDate,
        public ?DateTimeImmutable $dateApproved,
        public ?int $validityDays,
        public ?bool $isClosed,
        public ?QuoteArchiveReasonRef $archiveReason,
        public ?string $customerStage,
        public ?string $jobNo,
        public ?bool $isVariation,
        public ?int $linkedJobId,
        public ?QuoteForecast $forecast,
        public QuoteTotal|float|null $total,
        public ?QuoteTotals $totals,
        public ?array $tags,
        public ?bool $autoAdjustStatus,
        public ?array $customFields,
        public ?QuoteStc $stc,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $status = null;
        if (isset($data['Status'])) {
            if (is_array($data['Status'])) {
                $status = QuoteStatus::fromArray($data['Status']);
            } else {
                $status = $data['Status'];
            }
        }

        $total = null;
        if (isset($data['Total'])) {
            if (is_array($data['Total'])) {
                $total = QuoteTotal::fromArray($data['Total']);
            } else {
                $total = (float) $data['Total'];
            }
        }

        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? null,
            name: $data['Name'] ?? null,
            site: isset($data['Site']) ? QuoteSite::fromArray($data['Site']) : null,
            customer: isset($data['Customer']) ? QuoteCustomer::fromArray($data['Customer']) : null,
            additionalCustomers: isset($data['AdditionalCustomers']) ? array_map(
                fn (array $item) => QuoteCustomer::fromArray($item),
                $data['AdditionalCustomers']
            ) : null,
            customerContact: isset($data['CustomerContact']) ? QuoteContact::fromArray($data['CustomerContact']) : null,
            additionalContacts: isset($data['AdditionalContacts']) ? array_map(
                fn (array $item) => QuoteContact::fromArray($item),
                $data['AdditionalContacts']
            ) : null,
            siteContact: isset($data['SiteContact']) ? QuoteContact::fromArray($data['SiteContact']) : null,
            convertedFromLead: isset($data['ConvertedFromLead']) ? QuoteConvertedFromLead::fromArray($data['ConvertedFromLead']) : null,
            salesperson: isset($data['Salesperson']) ? StaffReference::fromArray($data['Salesperson']) : null,
            projectManager: isset($data['ProjectManager']) ? StaffReference::fromArray($data['ProjectManager']) : null,
            technicians: isset($data['Technicians']) ? array_map(
                fn (array $item) => StaffReference::fromArray($item),
                $data['Technicians']
            ) : null,
            technician: isset($data['Technician']) ? StaffReference::fromArray($data['Technician']) : null,
            status: $status,
            stage: $data['Stage'] ?? null,
            orderNo: $data['OrderNo'] ?? null,
            requestNo: $data['RequestNo'] ?? null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            dateIssued: isset($data['DateIssued']) ? new DateTimeImmutable($data['DateIssued']) : null,
            dueDate: isset($data['DueDate']) ? new DateTimeImmutable($data['DueDate']) : null,
            dateApproved: isset($data['DateApproved']) ? new DateTimeImmutable($data['DateApproved']) : null,
            validityDays: isset($data['ValidityDays']) ? (int) $data['ValidityDays'] : null,
            isClosed: $data['IsClosed'] ?? null,
            archiveReason: isset($data['ArchiveReason']) ? QuoteArchiveReasonRef::fromArray($data['ArchiveReason']) : null,
            customerStage: $data['CustomerStage'] ?? null,
            jobNo: $data['JobNo'] ?? null,
            isVariation: $data['IsVariation'] ?? null,
            linkedJobId: isset($data['LinkedJobId']) ? (int) $data['LinkedJobId'] : null,
            forecast: isset($data['Forecast']) ? QuoteForecast::fromArray($data['Forecast']) : null,
            total: $total,
            totals: isset($data['Totals']) ? QuoteTotals::fromArray($data['Totals']) : null,
            tags: isset($data['Tags']) ? array_map(
                fn (array $item) => Reference::fromArray($item),
                $data['Tags']
            ) : null,
            autoAdjustStatus: $data['AutoAdjustStatus'] ?? null,
            customFields: $data['CustomFields'] ?? null,
            stc: isset($data['STC']) ? QuoteStc::fromArray($data['STC']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
        );
    }
}
