<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters;

final readonly class CostCenterLockedInfo
{
    public function __construct(
        public ?string $type,
        public ?bool $isLocked,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['Type'] ?? null,
            isLocked: $data['IsLocked'] ?? null,
        );
    }
}
