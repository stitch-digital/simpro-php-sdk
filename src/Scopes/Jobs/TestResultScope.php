<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Jobs;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrders\Assets\TestResultAttachmentFileResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific test result, providing access to attachments.
 *
 * @example
 * // Access attachments for this test result
 * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenter(costCenterId: 5)
 *     ->workOrder(workOrderId: 99)->asset(assetId: 77)->testResult(testResultId: 55)->attachmentFiles()->list();
 */
final class TestResultScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $workOrderId,
        private readonly int|string $assetId,
        private readonly int|string $testResultId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access attachment files for this test result.
     */
    public function attachmentFiles(): TestResultAttachmentFileResource
    {
        return new TestResultAttachmentFileResource(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $this->assetId,
            $this->testResultId
        );
    }
}
