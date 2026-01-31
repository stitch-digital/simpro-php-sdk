<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Customers;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Customers\ServiceLevelAssetTypeResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific service level, providing access to nested resources.
 *
 * @example
 * // Access service level asset types
 * $connector->customers(companyId: 0)->customer(customerId: 123)->contract(contractId: 100)->serviceLevel(serviceLevelId: 10)->assetTypes()->get(5);
 */
final class ServiceLevelScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
        private readonly int|string $serviceLevelId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access asset types for this service level.
     */
    public function assetTypes(): ServiceLevelAssetTypeResource
    {
        return new ServiceLevelAssetTypeResource(
            $this->connector,
            $this->companyId,
            $this->customerId,
            $this->contractId,
            $this->serviceLevelId
        );
    }
}
