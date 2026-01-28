<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\ContractorJobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFolder;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Folders\CreateContractorJobAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Folders\DeleteContractorJobAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Folders\GetContractorJobAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Folders\ListContractorJobAttachmentFoldersRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\Attachments\Folders\UpdateContractorJobAttachmentFolderRequest;

/**
 * Resource for managing contractor job attachment folders.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractorJobAttachmentFolderResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int|string $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $contractorJobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment folders for this contractor job.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorJobAttachmentFoldersRequest(
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
     * Get a specific attachment folder.
     */
    public function get(int|string $folderId): AttachmentFolder
    {
        $request = new GetContractorJobAttachmentFolderRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId,
            $folderId
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Create a new attachment folder.
     *
     * @param  array<string, mixed>  $data
     * @return int The ID of the created folder
     */
    public function create(array $data): int
    {
        $request = new CreateContractorJobAttachmentFolderRequest(
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
     * Update an existing attachment folder.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $folderId, array $data): Response
    {
        $request = new UpdateContractorJobAttachmentFolderRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId,
            $folderId,
            $data
        );

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment folder.
     */
    public function delete(int|string $folderId): Response
    {
        $request = new DeleteContractorJobAttachmentFolderRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->contractorJobId,
            $folderId
        );

        return $this->connector->send($request);
    }
}
