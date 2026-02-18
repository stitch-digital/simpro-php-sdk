<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Quotes;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterAssetResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterCatalogResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterContractorJobResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterLaborResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterOneOffResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterPrebuildResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterScheduleResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterServiceFeeResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterTaskResource;
use Simpro\PhpSdk\Simpro\Resources\Quotes\CostCenters\QuoteCostCenterWorkOrderResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

final class QuoteCostCenterScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector, $companyId);
    }

    public function assets(): QuoteCostCenterAssetResource
    {
        return new QuoteCostCenterAssetResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }

    public function catalogs(): QuoteCostCenterCatalogResource
    {
        return new QuoteCostCenterCatalogResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }

    public function contractorJobs(): QuoteCostCenterContractorJobResource
    {
        return new QuoteCostCenterContractorJobResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }

    public function labor(): QuoteCostCenterLaborResource
    {
        return new QuoteCostCenterLaborResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }

    public function oneOffs(): QuoteCostCenterOneOffResource
    {
        return new QuoteCostCenterOneOffResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }

    public function prebuilds(): QuoteCostCenterPrebuildResource
    {
        return new QuoteCostCenterPrebuildResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }

    public function schedules(): QuoteCostCenterScheduleResource
    {
        return new QuoteCostCenterScheduleResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }

    public function serviceFees(): QuoteCostCenterServiceFeeResource
    {
        return new QuoteCostCenterServiceFeeResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }

    public function tasks(): QuoteCostCenterTaskResource
    {
        return new QuoteCostCenterTaskResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }

    public function workOrders(): QuoteCostCenterWorkOrderResource
    {
        return new QuoteCostCenterWorkOrderResource($this->connector, $this->companyId, $this->quoteId, $this->sectionId, $this->costCenterId);
    }
}
