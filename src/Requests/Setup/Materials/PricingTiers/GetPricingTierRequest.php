<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PricingTiers;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Setup\PricingTier;

/**
 * Get a specific PricingTier.
 */
final class GetPricingTierRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $pricingTierId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/materials/pricingTiers/{$this->pricingTierId}";
    }

    public function createDtoFromResponse(Response $response): PricingTier
    {
        return PricingTier::fromResponse($response);
    }
}
