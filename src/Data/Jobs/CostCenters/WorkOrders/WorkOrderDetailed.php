<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders;

use DateTimeImmutable;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Data\JobWorkOrders\JobWorkOrderBlock;

final readonly class WorkOrderDetailed
{
    /**
     * @param  array<mixed>  $materials
     * @param  array<JobWorkOrderBlock>  $blocks
     * @param  array<CustomField>  $customFields
     * @param  array<WorkOrderAssetReference>  $workOrderAssets
     */
    public function __construct(
        public int $id,
        public ?StaffReference $staff = null,
        public ?string $workOrderDate = null,
        public ?string $descriptionNotes = null,
        public ?string $materialNotes = null,
        public ?bool $approved = null,
        public array $materials = [],
        public array $blocks = [],
        public ?float $scheduledHrs = null,
        public ?string $scheduledStartTime = null,
        public ?DateTimeImmutable $iso8601ScheduledStartTime = null,
        public ?string $scheduledEndTime = null,
        public ?DateTimeImmutable $iso8601ScheduledEndTime = null,
        public ?DateTimeImmutable $dateModified = null,
        public array $customFields = [],
        public array $workOrderAssets = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            staff: isset($data['Staff']) ? StaffReference::fromArray($data['Staff']) : null,
            workOrderDate: $data['WorkOrderDate'] ?? null,
            descriptionNotes: $data['DescriptionNotes'] ?? null,
            materialNotes: $data['MaterialNotes'] ?? null,
            approved: $data['Approved'] ?? null,
            materials: $data['Materials'] ?? [],
            blocks: isset($data['Blocks']) ? array_map(
                fn (array $block) => JobWorkOrderBlock::fromArray($block),
                $data['Blocks']
            ) : [],
            scheduledHrs: isset($data['ScheduledHrs']) ? (float) $data['ScheduledHrs'] : null,
            scheduledStartTime: $data['ScheduledStartTime'] ?? null,
            iso8601ScheduledStartTime: isset($data['ISO8601ScheduledStartTime']) ? new DateTimeImmutable($data['ISO8601ScheduledStartTime']) : null,
            scheduledEndTime: $data['ScheduledEndTime'] ?? null,
            iso8601ScheduledEndTime: isset($data['ISO8601ScheduledEndTime']) ? new DateTimeImmutable($data['ISO8601ScheduledEndTime']) : null,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            customFields: isset($data['CustomFields']) ? array_map(
                fn (array $cf) => CustomField::fromArray($cf),
                $data['CustomFields']
            ) : [],
            workOrderAssets: isset($data['WorkOrderAssets']) ? array_map(
                fn (array $asset) => WorkOrderAssetReference::fromArray($asset),
                $data['WorkOrderAssets']
            ) : [],
        );
    }
}
