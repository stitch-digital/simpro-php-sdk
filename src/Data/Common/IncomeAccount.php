<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Common DTO for an income (chart of accounts) account reference on a billable line.
 */
final readonly class IncomeAccount
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $number = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            name: $data['Name'] ?? null,
            number: $data['Number'] ?? null,
        );
    }
}
