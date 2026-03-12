<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Tasks;

final readonly class TaskAssignee
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $type = null,
        public ?int $typeId = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
            type: $data['Type'] ?? null,
            typeId: isset($data['TypeId']) ? (int) $data['TypeId'] : null,
        );
    }
}
