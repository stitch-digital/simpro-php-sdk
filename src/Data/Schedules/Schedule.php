<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Schedules;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

/**
 * DTO for schedule details.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/schedules/{scheduleID}
 */
final readonly class Schedule
{
    /**
     * @param  array<ScheduleBlock>|null  $blocks
     */
    public function __construct(
        public int $id,
        public ?string $type,
        public ?string $reference,
        public ?float $totalHours,
        public ?string $notes,
        public ?StaffReference $staff,
        public ?string $date,
        public ?array $blocks,
        public ?string $href,
        public ?DateTimeImmutable $dateModified,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

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

        // Handle empty string for dateModified
        $dateModified = null;
        if (! empty($data['DateModified'])) {
            $dateModified = new DateTimeImmutable($data['DateModified']);
        }

        return new self(
            id: $data['ID'],
            type: $data['Type'] ?? null,
            reference: $data['Reference'] ?? null,
            totalHours: isset($data['TotalHours']) ? (float) $data['TotalHours'] : null,
            notes: $data['Notes'] ?? null,
            staff: ! empty($data['Staff']) ? StaffReference::fromArray($data['Staff']) : null,
            date: $data['Date'] ?? null,
            blocks: $blocks,
            href: $data['_href'] ?? null,
            dateModified: $dateModified,
        );
    }
}
