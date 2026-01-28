<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobStc
{
    public function __construct(
        public ?bool $stcsEligible,
        public ?bool $veecsEligible,
        public ?float $stcValue,
        public ?float $veecValue,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            stcsEligible: $data['STCsEligible'] ?? null,
            veecsEligible: $data['VEECsEligible'] ?? null,
            stcValue: isset($data['STCValue']) ? (float) $data['STCValue'] : null,
            veecValue: isset($data['VEECValue']) ? (float) $data['VEECValue'] : null,
        );
    }
}
