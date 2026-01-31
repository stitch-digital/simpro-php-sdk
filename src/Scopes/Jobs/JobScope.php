<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\Jobs;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\Jobs\AttachmentFileResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\AttachmentFolderResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\JobCustomFieldResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\JobLockResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\JobNoteResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\JobSectionResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\JobTaskResource;
use Simpro\PhpSdk\Simpro\Resources\Jobs\JobTimelineResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific job, providing access to nested resources.
 *
 * @example
 * // Access job sections
 * $connector->jobs(companyId: 0)->job(jobId: 123)->sections()->list();
 *
 * // Navigate to a specific section
 * $connector->jobs(companyId: 0)->job(jobId: 123)->section(sectionId: 1)->costCenters()->list();
 */
final class JobScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $jobId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access attachment files for this job.
     */
    public function attachmentFiles(): AttachmentFileResource
    {
        return new AttachmentFileResource($this->connector, $this->companyId, $this->jobId);
    }

    /**
     * Access attachment folders for this job.
     */
    public function attachmentFolders(): AttachmentFolderResource
    {
        return new AttachmentFolderResource($this->connector, $this->companyId, $this->jobId);
    }

    /**
     * Access custom fields for this job.
     */
    public function customFields(): JobCustomFieldResource
    {
        return new JobCustomFieldResource($this->connector, $this->companyId, $this->jobId);
    }

    /**
     * Access lock operations for this job.
     */
    public function lock(): JobLockResource
    {
        return new JobLockResource($this->connector, $this->companyId, $this->jobId);
    }

    /**
     * Access notes for this job.
     */
    public function notes(): JobNoteResource
    {
        return new JobNoteResource($this->connector, $this->companyId, $this->jobId);
    }

    /**
     * Access sections for this job.
     */
    public function sections(): JobSectionResource
    {
        return new JobSectionResource($this->connector, $this->companyId, $this->jobId);
    }

    /**
     * Navigate to a specific section scope.
     */
    public function section(int|string $sectionId): SectionScope
    {
        return new SectionScope($this->connector, $this->companyId, $this->jobId, $sectionId);
    }

    /**
     * Access tasks for this job.
     */
    public function tasks(): JobTaskResource
    {
        return new JobTaskResource($this->connector, $this->companyId, $this->jobId);
    }

    /**
     * Access timelines for this job.
     */
    public function timelines(): JobTimelineResource
    {
        return new JobTimelineResource($this->connector, $this->companyId, $this->jobId);
    }
}
