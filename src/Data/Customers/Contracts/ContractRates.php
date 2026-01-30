<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for contract rates information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}
 */
final readonly class ContractRates
{
    public function __construct(
        public ?Reference $serviceFee,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceFee: isset($data['ServiceFee']) ? Reference::fromArray($data['ServiceFee']) : null,
        );
    }
}
