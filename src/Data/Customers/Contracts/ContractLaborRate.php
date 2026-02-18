<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for contract labor rate.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/laborRates/
 */
final readonly class ContractLaborRate
{
    public function __construct(
        public ?Reference $laborRate,
        public bool $isDefault,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            laborRate: ! empty($data['LaborRate']) ? Reference::fromArray($data['LaborRate']) : null,
            isDefault: $data['IsDefault'] ?? false,
        );
    }
}
