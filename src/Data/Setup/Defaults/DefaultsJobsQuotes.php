<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class DefaultsJobsQuotes
{
    public function __construct(
        public ?Reference $defaultCostCenter,
        public bool $singleCostCenter,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            defaultCostCenter: isset($data['DefaultCostCenter']['ID'])
                ? Reference::fromArray($data['DefaultCostCenter'])
                : null,
            singleCostCenter: (bool) ($data['SingleCostCenter'] ?? false),
        );
    }
}
