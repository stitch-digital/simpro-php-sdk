<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Schedules;

use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * DTO for schedule list items.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/schedules/
 */
final readonly class ScheduleListItem
{
    /**
     * @param  array<ScheduleBlock>|null  $blocks
     */
    public function __construct(
        public int $id,
        public ?string $type,
        public ?string $reference,
        public ?float $totalHours,
        public ?StaffReference $staff,
        public ?string $date,
        public ?array $blocks,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $blocks = null;
        if (! empty($data['Blocks']) && is_array($data['Blocks'])) {
            $blocks = array_map(
                fn (array $block) => ScheduleBlock::fromArray($block),
                $data['Blocks']
            );
        }

        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? null,
            reference: $data['Reference'] ?? null,
            totalHours: isset($data['TotalHours']) ? (float) $data['TotalHours'] : null,
            staff: ! empty($data['Staff']) ? StaffReference::fromArray($data['Staff']) : null,
            date: $data['Date'] ?? null,
            blocks: $blocks,
        );
    }
}
