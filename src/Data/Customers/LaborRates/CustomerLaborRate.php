<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\LaborRates;

use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;

/**
 * DTO for a single customer labor rate (detailed view).
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/laborRates/{laborRateID}
 */
final readonly class CustomerLaborRate
{
    public function __construct(
        public Reference $laborRate,
        public float $cost,
        public float $markup,
        public bool $isDefault,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            laborRate: Reference::fromArray($data['LaborRate']),
            cost: isset($data['Cost']) ? (float) $data['Cost'] : 0.0,
            markup: isset($data['Markup']) ? (float) $data['Markup'] : 0.0,
            isDefault: $data['IsDefault'] ?? false,
        );
    }
}
