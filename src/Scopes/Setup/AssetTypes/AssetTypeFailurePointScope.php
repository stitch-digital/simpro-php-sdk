<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Setup\AssetTypes;

use Simpro\PhpSdk\Simpro\Resources\Setup\AssetTypes\AssetTypeRecommendationResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for navigating a specific failure point's nested resources.
 */
final class AssetTypeFailurePointScope extends AbstractScope
{
    public function __construct(
        \Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $assetTypeId,
        private readonly int|string $serviceLevelId,
        private readonly int|string $failurePointId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access recommendations for this failure point.
     */
    public function recommendations(): AssetTypeRecommendationResource
    {
        return new AssetTypeRecommendationResource(
            $this->connector,
            $this->companyId,
            $this->assetTypeId,
            $this->serviceLevelId,
            $this->failurePointId
        );
    }
}
