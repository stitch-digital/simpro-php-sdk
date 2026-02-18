<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\Tasks;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class JobTaskAssociatedJob
{
    public function __construct(
        public int $id,
        public ?Reference $costCenter,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            costCenter: ! empty($data['CostCenter']) ? Reference::fromArray($data['CostCenter']) : null,
        );
    }
}
