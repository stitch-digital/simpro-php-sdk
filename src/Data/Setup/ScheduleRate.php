<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * ScheduleRate DTO.
 */
final readonly class ScheduleRate
{
    public function __construct(
        public int $id,
        public string $name,
        public float $multiplier = 1.0,
        public bool $showInMobile = false,
        public bool $showInConnect = false,
        public bool $incOverhead = false,
        public bool $activityOnly = false,
        public ?string $scheduleColor = null,
        public int $displayOrder = 0,
        public bool $archived = false,
        public float $hourlyAllowance = 0.0,
        public float $payRateOverride = 0.0,
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
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            multiplier: (float) ($data['Multiplier'] ?? 1.0),
            showInMobile: (bool) ($data['ShowInMobile'] ?? false),
            showInConnect: (bool) ($data['ShowInConnect'] ?? false),
            incOverhead: (bool) ($data['IncOverhead'] ?? false),
            activityOnly: (bool) ($data['ActivityOnly'] ?? false),
            scheduleColor: $data['ScheduleColor'] ?? null,
            displayOrder: (int) ($data['DisplayOrder'] ?? 0),
            archived: (bool) ($data['Archived'] ?? false),
            hourlyAllowance: (float) ($data['HourlyAllowance'] ?? 0.0),
            payRateOverride: (float) ($data['PayRateOverride'] ?? 0.0),
        );
    }
}
