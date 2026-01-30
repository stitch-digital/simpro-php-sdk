<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * Cost center rates DTO.
 */
final readonly class CostCenterRates
{
    public function __construct(
        public ?Reference $serviceFee = null,
        public ?Reference $laborRate = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceFee: isset($data['ServiceFee']['ID']) ? Reference::fromArray($data['ServiceFee']) : null,
            laborRate: isset($data['LaborRate']['ID']) ? Reference::fromArray($data['LaborRate']) : null,
        );
    }
}
