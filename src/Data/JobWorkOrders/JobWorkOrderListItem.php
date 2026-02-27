<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\JobWorkOrders;

use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

final readonly class JobWorkOrderListItem
{
    public function __construct(
        public int $id,
        public ?StaffReference $staff = null,
        public ?string $workOrderDate = null,
        public ?JobWorkOrderProject $project = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            staff: isset($data['Staff']) ? StaffReference::fromArray($data['Staff']) : null,
            workOrderDate: $data['WorkOrderDate'] ?? null,
            project: isset($data['Project']) ? JobWorkOrderProject::fromArray($data['Project']) : null,
        );
    }
}
