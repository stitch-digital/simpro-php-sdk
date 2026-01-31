<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Setup\AssetTypes;

use Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes\AssetTypeFailurePointResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for navigating a specific service level's nested resources.
 */
final class AssetTypeServiceLevelScope extends AbstractScope
{
    public function __construct(
        \Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $serviceLevelId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access failure points for this service level.
     */
    public function failurePoints(): AssetTypeFailurePointResource
    {
        return new AssetTypeFailurePointResource($this->connector, $this->companyId, $this->assetTypeId, $this->serviceLevelId);
    }

    /**
     * Navigate to a specific failure point.
     */
    public function failurePoint(int|string $failurePointId): AssetTypeFailurePointScope
    {
        return new AssetTypeFailurePointScope($this->connector, $this->companyId, $this->assetTypeId, $this->serviceLevelId, $failurePointId);
    }
}
