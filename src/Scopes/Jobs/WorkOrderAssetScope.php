<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Jobs;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrders\Assets\TestResultResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific work order asset, providing access to test results.
 *
 * @example
 * // Access test results for this asset
 * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenter(costCenterId: 5)
 *     ->workOrder(workOrderId: 99)->asset(assetId: 77)->testResults()->list();
 */
final class WorkOrderAssetScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int $jobId,
        private readonly int $sectionId,
        private readonly int $costCenterId,
        private readonly int $workOrderId,
        private readonly int $assetId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access test results for this asset.
     */
    public function testResults(): TestResultResource
    {
        return new TestResultResource(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $this->assetId
        );
    }

    /**
     * Navigate to a specific test result scope for accessing attachments.
     */
    public function testResult(int $testResultId): TestResultScope
    {
        return new TestResultScope(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $this->assetId,
            $testResultId
        );
    }
}
