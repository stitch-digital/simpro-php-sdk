<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Jobs;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\ContractorJobs\ContractorJobAttachmentFileResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\ContractorJobs\ContractorJobAttachmentFolderResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\ContractorJobs\ContractorJobCustomFieldResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific contractor job, providing access to nested resources.
 *
 * @example
 * // Access attachments for this contractor job
 * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenter(costCenterId: 5)
 *     ->contractorJob(contractorJobId: 99)->attachmentFiles()->list();
 */
final class ContractorJobScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $contractorJobId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access attachment files for this contractor job.
     */
    public function attachmentFiles(): ContractorJobAttachmentFileResource
    {
        return new ContractorJobAttachmentFileResource(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId
        );
    }

    /**
     * Access attachment folders for this contractor job.
     */
    public function attachmentFolders(): ContractorJobAttachmentFolderResource
    {
        return new ContractorJobAttachmentFolderResource(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId
        );
    }

    /**
     * Access custom fields for this contractor job.
     */
    public function customFields(): ContractorJobCustomFieldResource
    {
        return new ContractorJobCustomFieldResource(
            $this->connector,
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId
        );
    }
}
