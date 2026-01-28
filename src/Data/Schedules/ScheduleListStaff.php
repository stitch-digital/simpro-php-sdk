<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Schedules;

final readonly class ScheduleListStaff
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public ?int $typeId,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? '',
            type: $data['Type'] ?? '',
            typeId: $data['TypeId'] ?? null,
        );
    }
}
