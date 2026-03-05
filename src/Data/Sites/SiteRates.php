<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Sites;

final readonly class SiteRates
{
    public function __construct(
        public ?float $serviceFee = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceFee: isset($data['ServiceFee']) ? (float) $data['ServiceFee'] : null,
        );
    }
}
