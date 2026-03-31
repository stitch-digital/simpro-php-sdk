<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorJobs;

final readonly class ContractorJobContractor
{
    public function __construct(
        public int $id,
        public string $name,
        public string $contactName,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? '',
            contactName: $data['ContactName'] ?? '',
        );
    }
}
