<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\CustomerAssets;

final readonly class CustomerAssetContract
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?string $contractNo = null,
        public ?bool $expired = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? null,
            startDate: $data['StartDate'] ?? null,
            endDate: $data['EndDate'] !== '' ? ($data['EndDate'] ?? null) : null,
            contractNo: $data['ContractNo'] ?? null,
            expired: $data['Expired'] ?? null,
        );
    }
}
