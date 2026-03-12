<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Tasks;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class TaskAssociatedJob
{
    public function __construct(
        public int $id,
        public ?Reference $costCenter = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            costCenter: isset($data['CostCenter']) && is_array($data['CostCenter']) && ! empty($data['CostCenter'])
                ? Reference::fromArray($data['CostCenter'])
                : null,
        );
    }
}
