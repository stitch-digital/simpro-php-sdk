<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ActivitySchedules;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * DTO for activity schedule details.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/activitySchedules/{scheduleID}
 */
final readonly class ActivitySchedule
{
    /**
     * @param  array<ActivityScheduleBlock>|null  $blocks
     */
    public function __construct(
        public int $id,
        public ?float $totalHours,
        public ?string $notes,
        public ?bool $isLocked,
        public ?int $recurringScheduleId,
        public ?StaffReference $staff,
        public ?string $date,
        public ?array $blocks,
        public ?DateTimeImmutable $dateModified,
        public ?Reference $activity,
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
        $blocks = null;
        if (isset($data['Blocks']) && is_array($data['Blocks'])) {
            $blocks = array_map(
                fn (array $block) => ActivityScheduleBlock::fromArray($block),
                $data['Blocks']
            );
        }

        return new self(
            id: $data['ID'],
            totalHours: isset($data['TotalHours']) ? (float) $data['TotalHours'] : null,
            notes: $data['Notes'] ?? null,
            isLocked: $data['IsLocked'] ?? null,
            recurringScheduleId: $data['RecurringScheduleID'] ?? null,
            staff: isset($data['Staff']) ? StaffReference::fromArray($data['Staff']) : null,
            date: $data['Date'] ?? null,
            blocks: $blocks,
            dateModified: isset($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            activity: isset($data['Activity']) ? Reference::fromArray($data['Activity']) : null,
        );
    }
}
