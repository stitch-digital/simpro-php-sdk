<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PricingTiers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a PricingTier.
 */
final class DeletePricingTierRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $pricingTierId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/materials/pricingTiers/{$this->pricingTierId}";
    }
}
