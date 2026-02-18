<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobTotal;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobTotals;

final readonly class CostCenter
{
    /**
     * @param  array<CostCenterVendorOrder>|null  $vendorOrders
     */
    public function __construct(
        public int $id,
        public ?Reference $costCenter,
        public ?int $jobId,
        public ?string $name,
        public ?string $header,
        public ?Reference $site,
        public ?string $stage,
        public ?string $description,
        public ?string $notes,
        public ?string $orderNo,
        public ?DateTimeImmutable $startDate,
        public ?DateTimeImmutable $endDate,
        public ?bool $autoAdjustDates,
        public ?int $displayOrder,
        public ?bool $variation,
        public ?DateTimeImmutable $variationApprovalDate,
        public ?bool $itemsLocked,
        public ?CostCenterLockedInfo $lockedInfo,
        public ?array $vendorOrders,
        public ?JobTotal $total,
        public ?JobTotals $totals,
        public ?DateTimeImmutable $dateModified,
        public ?int $percentComplete,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
            jobId: isset($data['JobID']) ? (int) $data['JobID'] : null,
            name: $data['Name'] ?? null,
            header: $data['Header'] ?? null,
            site: ! empty($data['Site']) ? Reference::fromArray($data['Site']) : null,
            stage: $data['Stage'] ?? null,
            description: $data['Description'] ?? null,
            notes: $data['Notes'] ?? null,
            orderNo: $data['OrderNo'] ?? null,
            startDate: ! empty($data['StartDate']) ? new DateTimeImmutable($data['StartDate']) : null,
            endDate: ! empty($data['EndDate']) ? new DateTimeImmutable($data['EndDate']) : null,
            autoAdjustDates: $data['AutoAdjustDates'] ?? null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            variation: $data['Variation'] ?? null,
            variationApprovalDate: ! empty($data['VariationApprovalDate']) ? new DateTimeImmutable($data['VariationApprovalDate']) : null,
            itemsLocked: $data['ItemsLocked'] ?? null,
            lockedInfo: ! empty($data['LockedInfo']) ? CostCenterLockedInfo::fromArray($data['LockedInfo']) : null,
            vendorOrders: isset($data['VendorOrders']) ? array_map(
                fn (array $item) => CostCenterVendorOrder::fromArray($item),
                $data['VendorOrders']
            ) : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? JobTotal::fromArray($data['Total']) : null,
            totals: ! empty($data['Totals']) ? JobTotals::fromArray($data['Totals']) : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            percentComplete: isset($data['PercentComplete']) ? (int) $data['PercentComplete'] : null,
        );
    }
}
