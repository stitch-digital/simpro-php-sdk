<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs\CostCenters\WorkOrders\Assets;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFile;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\TestResults\Attachments\Files\CreateTestResultAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\TestResults\Attachments\Files\DeleteTestResultAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\TestResults\Attachments\Files\GetTestResultAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\TestResults\Attachments\Files\ListTestResultAttachmentFilesRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\WorkOrders\Assets\TestResults\Attachments\Files\UpdateTestResultAttachmentFileRequest;

/**
 * Resource for managing test result attachment files.
 *
 * @property AbstractSimproConnector $connector
 */
final class TestResultAttachmentFileResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $jobId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $workOrderId,
        private readonly int|string $assetId,
        private readonly int|string $testResultId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment files for this test result.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListTestResultAttachmentFilesRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $this->assetId,
            $this->testResultId
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
        $request = new GetTestResultAttachmentFileRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $this->assetId,
            $this->testResultId,
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
        $request = new CreateTestResultAttachmentFileRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $this->assetId,
            $this->testResultId,
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
        $request = new UpdateTestResultAttachmentFileRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $this->assetId,
            $this->testResultId,
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
        $request = new DeleteTestResultAttachmentFileRequest(
            $this->companyId,
            $this->jobId,
            $this->sectionId,
            $this->costCenterId,
            $this->workOrderId,
            $this->assetId,
            $this->testResultId,
            $fileId
        );

        return $this->connector->send($request);
    }
}
