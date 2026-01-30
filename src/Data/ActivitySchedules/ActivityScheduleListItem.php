<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ActivitySchedules;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * DTO for activity schedule list items.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/activitySchedules/
 */
final readonly class ActivityScheduleListItem
{
    public function __construct(
        public int $id,
        public ?float $totalHours,
        public ?StaffReference $staff,
        public ?string $date,
        public ?Reference $activity,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            totalHours: isset($data['TotalHours']) ? (float) $data['TotalHours'] : null,
            staff: isset($data['Staff']) ? StaffReference::fromArray($data['Staff']) : null,
            date: $data['Date'] ?? null,
            activity: isset($data['Activity']) ? Reference::fromArray($data['Activity']) : null,
        );
    }
}
