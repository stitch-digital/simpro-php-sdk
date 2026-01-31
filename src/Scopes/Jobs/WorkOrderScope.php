<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Jobs;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrders\MobileSignatureResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrders\WorkOrderAssetResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrders\WorkOrderAttachmentFileResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrders\WorkOrderCustomFieldResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific work order, providing access to nested resources.
 *
 * @example
 * // Access assets for this work order
 * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenter(costCenterId: 5)
 *     ->workOrder(workOrderId: 99)->assets()->list();
 */
final class WorkOrderScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $workOrderId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access assets for this work order.
     */
    public function assets(): WorkOrderAssetResource
    {
        return new WorkOrderAssetResource(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId
        );
    }

    /**
     * Navigate to a specific asset scope for accessing test results.
     */
    public function asset(int|string $assetId): WorkOrderAssetScope
    {
        return new WorkOrderAssetScope(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $assetId
        );
    }

    /**
     * Access attachment files for this work order.
     */
    public function attachmentFiles(): WorkOrderAttachmentFileResource
    {
        return new WorkOrderAttachmentFileResource(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId
        );
    }

    /**
     * Access custom fields for this work order.
     */
    public function customFields(): WorkOrderCustomFieldResource
    {
        return new WorkOrderCustomFieldResource(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId
        );
    }

    /**
     * Access mobile signatures for this work order.
     */
    public function mobileSignatures(): MobileSignatureResource
    {
        return new MobileSignatureResource(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId
        );
    }
}
