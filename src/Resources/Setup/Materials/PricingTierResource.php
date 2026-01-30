<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Setup\Materials;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\PricingTier;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PricingTiers\CreatePricingTierRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PricingTiers\DeletePricingTierRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PricingTiers\GetPricingTierRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PricingTiers\ListPricingTiersRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PricingTiers\UpdatePricingTierRequest;

/**
 * Resource for managing PricingTiers.
 *
 * @property AbstractSimproConnector $connector
 */
final class PricingTierResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all.
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListPricingTiersRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific item.
     *
     * @param  array<string>|null  $columns
     */
    public function get(int|string $pricingTierId, ?array $columns = null): PricingTier
    {
        $request = new GetPricingTierRequest($this->companyId, $pricingTierId);

        if ($columns !== null) {
            $request->query()->add('columns', implode(',', $columns));
        }

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new item.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): int
    {
        $request = new CreatePricingTierRequest($this->companyId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an item.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $pricingTierId, array $data): Response
    {
        $request = new UpdatePricingTierRequest($this->companyId, $pricingTierId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an item.
     */
    public function delete(int|string $pricingTierId): Response
    {
        $request = new DeletePricingTierRequest($this->companyId, $pricingTierId);

        return $this->connector->send($request);
    }
}
