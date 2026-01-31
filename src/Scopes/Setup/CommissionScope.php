<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Setup;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Setup\Commissions\ListCommissionsRequest;
use Simpro\PhpSdk\Simpro\Resources\Setup\AdvancedCommissionResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\BasicCommissionResource;

/**
 * Scope for navigating commission resources.
 */
final class CommissionScope
{
    public function __construct(
        private readonly AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {}

    /**
     * List all commissions (basic and advanced combined).
     *
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListCommissionsRequest($this->companyId);

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Access basic commission endpoints.
     */
    public function basic(): BasicCommissionResource
    {
        return new BasicCommissionResource($this->connector, $this->companyId);
    }

    /**
     * Access advanced commission endpoints.
     */
    public function advanced(): AdvancedCommissionResource
    {
        return new AdvancedCommissionResource($this->connector, $this->companyId);
    }
}
