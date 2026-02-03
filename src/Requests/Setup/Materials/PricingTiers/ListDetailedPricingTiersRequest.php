<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PricingTiers;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\PricingTier;

/**
 * List all pricing tiers with full details.
 */
final class ListDetailedPricingTiersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for pricing tiers.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'PriceBased',
        'DefaultMarkup',
        'Default',
        'TierType',
        'ScaledTierPricing',
        'DisplayOrder',
        'Archived',
        'DateModified',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/materials/pricingTiers/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<PricingTier>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): PricingTier => PricingTier::fromArray($item),
            $data
        );
    }
}
