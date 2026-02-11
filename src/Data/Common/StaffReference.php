<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Common DTO for staff references with type information.
 *
 * Used for AddedBy, CreatedBy, and similar fields where the staff member
 * can be an employee, contractor, or plant.
 */
final readonly class StaffReference
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $type = null,
        public ?int $typeId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? null,
            type: $data['Type'] ?? null,
            typeId: isset($data['TypeId']) ? (int) $data['TypeId'] : null,
        );
    }

    /**
     * Check if this is an employee reference.
     */
    public function isEmployee(): bool
    {
        return $this->type === 'employee';
    }

    /**
     * Check if this is a contractor reference.
     */
    public function isContractor(): bool
    {
        return $this->type === 'contractor';
    }

    /**
     * Check if this is a plant reference.
     */
    public function isPlant(): bool
    {
        return $this->type === 'plant';
    }
}
