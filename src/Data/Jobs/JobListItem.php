<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobListItem
{
    public function __construct(
        public int $id,
        public string $type,
        public ?string $name,
        public ?string $site,
        public ?string $siteId,
        public ?string $status,
        public ?string $stage,
        public ?string $customer,
        public ?string $customerId,
        public ?string $dateIssued,
        public ?float $total,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? '',
            name: $data['Name'] ?? null,
            site: $data['Site'] ?? null,
            siteId: $data['SiteID'] ?? null,
            status: $data['Status'] ?? null,
            stage: $data['Stage'] ?? null,
            customer: $data['Customer'] ?? null,
            customerId: $data['CustomerID'] ?? null,
            dateIssued: $data['DateIssued'] ?? null,
            total: isset($data['Total']) ? (float) $data['Total'] : null,
        );
    }
}
