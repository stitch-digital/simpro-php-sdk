<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Jobs;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\AssetResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\CatalogResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\ContractorJobResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\CostCenterLockResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\CostCenterScheduleResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\CostCenterTaskResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\LaborResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\OneOffResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\PrebuildResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\ServiceFeeResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\StockResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrderResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific cost center, providing access to nested resources.
 *
 * @example
 * // Access labor for this cost center
 * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenter(costCenterId: 5)->labor()->list();
 *
 * // Access work orders
 * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenter(costCenterId: 5)->workOrders()->list();
 */
final class CostCenterScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access assets for this cost center.
     */
    public function assets(): AssetResource
    {
        return new AssetResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access catalogs for this cost center.
     */
    public function catalogs(): CatalogResource
    {
        return new CatalogResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access contractor jobs for this cost center.
     */
    public function contractorJobs(): ContractorJobResource
    {
        return new ContractorJobResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Navigate to a specific contractor job scope.
     */
    public function contractorJob(int|string $contractorJobId): ContractorJobScope
    {
        return new ContractorJobScope($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $contractorJobId);
    }

    /**
     * Access labor for this cost center.
     */
    public function labor(): LaborResource
    {
        return new LaborResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access lock operations for this cost center.
     */
    public function lock(): CostCenterLockResource
    {
        return new CostCenterLockResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access one-offs for this cost center.
     */
    public function oneOffs(): OneOffResource
    {
        return new OneOffResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access prebuilds for this cost center.
     */
    public function prebuilds(): PrebuildResource
    {
        return new PrebuildResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access schedules for this cost center.
     */
    public function schedules(): CostCenterScheduleResource
    {
        return new CostCenterScheduleResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access service fees for this cost center.
     */
    public function serviceFees(): ServiceFeeResource
    {
        return new ServiceFeeResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access stock for this cost center.
     */
    public function stock(): StockResource
    {
        return new StockResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access tasks for this cost center.
     */
    public function tasks(): CostCenterTaskResource
    {
        return new CostCenterTaskResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Access work orders for this cost center.
     */
    public function workOrders(): WorkOrderResource
    {
        return new WorkOrderResource($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId);
    }

    /**
     * Navigate to a specific work order scope.
     */
    public function workOrder(int|string $workOrderId): WorkOrderScope
    {
        return new WorkOrderScope($this->connector, $this->companyId, $this->jobId, $this->sectionId, $this->costCenterId, $workOrderId);
    }
}
