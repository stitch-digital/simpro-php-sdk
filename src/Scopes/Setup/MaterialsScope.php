<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Setup;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Setup\Materials\PricingTierResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\Materials\PurchasingStageResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\Materials\StockTakeReasonResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\Materials\StockTransferReasonResource;
use Simpro\PhpSdk\Simpro\Resources\Setup\Materials\UomResource;

/**
 * Scope for navigating materials-related setup resources.
 */
final class MaterialsScope
{
    public function __construct(
        private readonly AbstractSimproConnector $connector,
        private readonly int $companyId,
    ) {}

    /**
     * Access pricing tier endpoints.
     */
    public function pricingTiers(): PricingTierResource
    {
        return new PricingTierResource($this->connector, $this->companyId);
    }

    /**
     * Access purchasing stage endpoints.
     */
    public function purchasingStages(): PurchasingStageResource
    {
        return new PurchasingStageResource($this->connector, $this->companyId);
    }

    /**
     * Access stock take reason endpoints.
     */
    public function stockTakeReasons(): StockTakeReasonResource
    {
        return new StockTakeReasonResource($this->connector, $this->companyId);
    }

    /**
     * Access stock transfer reason endpoints.
     */
    public function stockTransferReasons(): StockTransferReasonResource
    {
        return new StockTransferReasonResource($this->connector, $this->companyId);
    }

    /**
     * Access unit of measurement endpoints.
     */
    public function uoms(): UomResource
    {
        return new UomResource($this->connector, $this->companyId);
    }
}
