<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

use DateTimeImmutable;
use Saloon\Http\Response;

final readonly class Job
{
    /**
     * @param  array<JobContact>|null  $additionalContacts
     * @param  array<JobTag>|null  $tags
     * @param  array<JobStaff>|null  $technicians
     * @param  array<JobReference>|null  $linkedVariations
     * @param  array<JobSection>|null  $sections
     * @param  array<JobCustomField>|null  $customFields
     */
    public function __construct(
        public int $id,
        public string $type,
        public ?string $name,
        public ?JobCustomer $customer,
        public ?JobContract $customerContract,
        public ?JobContact $customerContact,
        public ?array $additionalContacts,
        public ?JobSite $site,
        public ?JobContact $siteContact,
        public ?string $orderNo,
        public ?string $requestNo,
        public ?string $description,
        public ?string $notes,
        public ?DateTimeImmutable $dateIssued,
        public ?DateTimeImmutable $dueDate,
        public ?string $dueTime,
        public ?array $tags,
        public ?JobStaff $salesperson,
        public ?JobStaff $projectManager,
        public ?array $technicians,
        public ?JobStaff $technician,
        public ?string $stage,
        public JobStatus|string|null $status,
        public ?string $responseTime,
        public ?bool $isVariation,
        public ?array $linkedVariations,
        public ?JobReference $convertedFromQuote,
        public ?JobReference $convertedFrom,
        public ?array $sections,
        public ?DateTimeImmutable $dateModified,
        public ?bool $autoAdjustStatus,
        public ?bool $isRetentionEnabled,
        public JobTotal|float|null $total,
        public ?JobTotals $totals,
        public ?array $customFields,
        public ?JobStc $stc,
        public ?DateTimeImmutable $completedDate,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        // Handle Status which can be string or object
        $status = null;
        if (isset($data['Status'])) {
            if (is_array($data['Status'])) {
                $status = JobStatus::fromArray($data['Status']);
            } else {
                $status = $data['Status'];
            }
        }

        // Handle Total which can be float or object
        $total = null;
        if (isset($data['Total'])) {
            if (is_array($data['Total'])) {
                $total = JobTotal::fromArray($data['Total']);
            } else {
                $total = (float) $data['Total'];
            }
        }

        // Handle ResponseTime which can be string or empty object
        $responseTime = null;
        if (isset($data['ResponseTime'])) {
            if (is_string($data['ResponseTime'])) {
                $responseTime = $data['ResponseTime'];
            } elseif (is_array($data['ResponseTime']) && ! empty($data['ResponseTime'])) {
                $responseTime = json_encode($data['ResponseTime']);
            }
        }

        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? '',
            name: $data['Name'] ?? null,
            customer: isset($data['Customer']) ? JobCustomer::fromArray($data['Customer']) : null,
            customerContract: isset($data['CustomerContract']) ? JobContract::fromArray($data['CustomerContract']) : null,
            customerContact: isset($data['CustomerContact']) ? JobContact::fromArray($data['CustomerContact']) : null,
            additionalContacts: isset($data['AdditionalContacts']) ? array_map(
                fn (array $item) => JobContact::fromArray($item),
                $data['AdditionalContacts']
            ) : null,
            site: isset($data['Site']) ? JobSite::fromArray($data['Site']) : null,
            siteContact: isset($data['SiteContact']) ? JobContact::fromArray($data['SiteContact']) : null,
            orderNo: $data['OrderNo'] ?? null,
            requestNo: $data['RequestNo'] ?? null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            dateIssued: isset($data['DateIssued']) ? new DateTimeImmutable($data['DateIssued']) : null,
            dueDate: isset($data['DueDate']) ? new DateTimeImmutable($data['DueDate']) : null,
            dueTime: $data['DueTime'] ?? null,
            tags: isset($data['Tags']) ? array_map(
                fn (array $item) => JobTag::fromArray($item),
                $data['Tags']
            ) : null,
            salesperson: isset($data['Salesperson']) ? JobStaff::fromArray($data['Salesperson']) : null,
            projectManager: isset($data['ProjectManager']) ? JobStaff::fromArray($data['ProjectManager']) : null,
            technicians: isset($data['Technicians']) ? array_map(
                fn (array $item) => JobStaff::fromArray($item),
                $data['Technicians']
            ) : null,
            technician: isset($data['Technician']) ? JobStaff::fromArray($data['Technician']) : null,
            stage: $data['Stage'] ?? null,
            status: $status,
            responseTime: $responseTime,
            isVariation: $data['IsVariation'] ?? null,
            linkedVariations: isset($data['LinkedVariations']) ? array_map(
                fn (array $item) => JobReference::fromArray($item),
                $data['LinkedVariations']
            ) : null,
            convertedFromQuote: isset($data['ConvertedFromQuote']) ? JobReference::fromArray($data['ConvertedFromQuote']) : null,
            convertedFrom: isset($data['ConvertedFrom']) ? JobReference::fromArray($data['ConvertedFrom']) : null,
            sections: isset($data['Sections']) ? array_map(
                fn (array $item) => JobSection::fromArray($item),
                $data['Sections']
            ) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            autoAdjustStatus: $data['AutoAdjustStatus'] ?? null,
            isRetentionEnabled: $data['IsRetentionEnabled'] ?? null,
            total: $total,
            totals: isset($data['Totals']) ? JobTotals::fromArray($data['Totals']) : null,
            customFields: isset($data['CustomFields']) ? array_map(
                fn (array $item) => JobCustomField::fromArray($item),
                $data['CustomFields']
            ) : null,
            stc: isset($data['STC']) ? JobStc::fromArray($data['STC']) : null,
            completedDate: isset($data['CompletedDate']) ? new DateTimeImmutable($data['CompletedDate']) : null,
        );
    }
}
