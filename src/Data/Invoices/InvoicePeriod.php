<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

use DateTimeImmutable;

final readonly class InvoicePeriod
{
    public function __construct(
        public ?DateTimeImmutable $startDate = null,
        public ?DateTimeImmutable $endDate = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            startDate: ! empty($data['StartDate']) ? new DateTimeImmutable($data['StartDate']) : null,
            endDate: ! empty($data['EndDate']) ? new DateTimeImmutable($data['EndDate']) : null,
        );
    }
}
