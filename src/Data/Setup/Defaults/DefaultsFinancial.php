<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsFinancial
{
    public function __construct(
        public ?DefaultsAccounts $accounts,
        public ?DefaultsInvoicing $invoicing,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accounts: ! empty($data['Accounts']) ? DefaultsAccounts::fromArray($data['Accounts']) : null,
            invoicing: ! empty($data['Invoicing']) ? DefaultsInvoicing::fromArray($data['Invoicing']) : null,
        );
    }
}
