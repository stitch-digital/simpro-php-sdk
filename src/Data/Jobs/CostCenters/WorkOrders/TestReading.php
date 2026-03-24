<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\WorkOrders;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

final readonly class TestReading
{
    public function __construct(
        public ?Reference $testReading = null,
        public mixed $value = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            testReading: isset($data['TestReading']) ? Reference::fromArray($data['TestReading']) : null,
            value: $data['Value'] ?? null,
        );
    }
}
