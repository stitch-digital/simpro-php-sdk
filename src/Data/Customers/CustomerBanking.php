<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

/**
 * Customer banking/payment information.
 */
final readonly class CustomerBanking
{
    public function __construct(
        public ?string $bsb = null,
        public ?string $accountNumber = null,
        public ?string $accountName = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            bsb: $data['BSB'] ?? null,
            accountNumber: $data['AccountNumber'] ?? null,
            accountName: $data['AccountName'] ?? null,
        );
    }
}
