<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\ContractorJobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFile;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Files\CreateContractorJobAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Files\DeleteContractorJobAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Files\GetContractorJobAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Files\ListContractorJobAttachmentFilesRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Files\UpdateContractorJobAttachmentFileRequest;

/**
 * Resource for managing contractor job attachment files.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractorJobAttachmentFileResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $contractorJobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment files for this contractor job.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorJobAttachmentFilesRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId
        );

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            $request->query()->add($key, (string) $value);
        }

        return new QueryBuilder($this->connector, $request);
    }

    /**
     * Get a specific attachment file.
     */
    public function get(int|string $fileId): AttachmentFile
    {
        $request = new GetContractorJobAttachmentFileRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId,
            $fileId
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new attachment file.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created file
     */
    public function create(array $data): int
    {
        $request = new CreateContractorJobAttachmentFileRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId,
            $data
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment file.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $fileId, array $data): Response
    {
        $request = new UpdateContractorJobAttachmentFileRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId,
            $fileId,
            $data
        );

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment file.
     */
    public function delete(int|string $fileId): Response
    {
        $request = new DeleteContractorJobAttachmentFileRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId,
            $fileId
        );

        return $this->connector->send($request);
    }
}
