<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices;

use DateTimeImmutable;

final readonly class RecurringInvoiceContract
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?DateTimeImmutable $startDate,
        public ?DateTimeImmutable $endDate,
        public ?string $contractNo,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
            startDate: ! empty($data['StartDate']) ? new DateTimeImmutable($data['StartDate']) : null,
            endDate: ! empty($data['EndDate']) ? new DateTimeImmutable($data['EndDate']) : null,
            contractNo: $data['ContractNo'] ?? null,
        );
    }
}
