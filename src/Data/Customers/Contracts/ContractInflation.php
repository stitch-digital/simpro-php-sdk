<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

use Saloon\Http\Response;

/**
 * DTO for contract inflation.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}/inflation/
 */
final readonly class ContractInflation
{
    public function __construct(
        public int $id,
        public string $date,
        public float $amount,
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
            id: $data['ID'],
            date: $data['Date'] ?? '',
            amount: isset($data['Amount']) ? (float) $data['Amount'] : 0.0,
        );
    }
}
