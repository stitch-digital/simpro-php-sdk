<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

final readonly class QuoteForecast
{
    public function __construct(
        public ?int $year,
        public ?int $month,
        public ?int $percent,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            year: isset($data['Year']) ? (int) $data['Year'] : null,
            month: isset($data['Month']) ? (int) $data['Month'] : null,
            percent: isset($data['Percent']) ? (int) $data['Percent'] : null,
        );
    }
}
