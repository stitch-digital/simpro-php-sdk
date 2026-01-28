<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Schedules;

final readonly class ScheduleListItem
{
    /**
     * @param  array<ScheduleBlock>  $blocks
     */
    public function __construct(
        public int $id,
        public string $type,
        public ?string $reference,
        public float $totalHours,
        public ?ScheduleListStaff $staff,
        public ?string $date,
        public array $blocks,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? '',
            reference: $data['Reference'] ?? null,
            totalHours: (float) ($data['TotalHours'] ?? 0),
            staff: isset($data['Staff']) ? ScheduleListStaff::fromArray($data['Staff']) : null,
            date: $data['Date'] ?? null,
            blocks: isset($data['Blocks']) ? array_map(
                fn (array $block) => ScheduleBlock::fromArray($block),
                $data['Blocks']
            ) : [],
        );
    }
}
