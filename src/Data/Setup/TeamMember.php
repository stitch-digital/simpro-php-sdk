<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

final readonly class TeamMember
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public int $typeId,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? '',
            type: $data['Type'] ?? '',
            typeId: (int) ($data['TypeId'] ?? 0),
        );
    }
}
