<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobContract
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $startDate,
        public ?string $endDate,
        public ?string $contractNo,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            startDate: $data['StartDate'] ?? null,
            endDate: $data['EndDate'] ?? null,
            contractNo: $data['ContractNo'] ?? null,
        );
    }
}
