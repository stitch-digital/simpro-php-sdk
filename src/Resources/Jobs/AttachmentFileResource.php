<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFile;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Files\CreateAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Files\DeleteAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Files\GetAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Files\ListAttachmentFilesRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Files\UpdateAttachmentFileRequest;

/**
 * Resource for managing job attachment files.
 *
 * @property AbstractSimproConnector $connector
 */
final class AttachmentFileResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $jobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment files for this job.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListAttachmentFilesRequest($this->companyId, $this->jobId);

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
        $request = new GetAttachmentFileRequest($this->companyId, $this->jobId, $fileId);

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
        $request = new CreateAttachmentFileRequest($this->companyId, $this->jobId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment file.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $fileId, array $data): Response
    {
        $request = new UpdateAttachmentFileRequest($this->companyId, $this->jobId, $fileId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment file.
     */
    public function delete(int|string $fileId): Response
    {
        $request = new DeleteAttachmentFileRequest($this->companyId, $this->jobId, $fileId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple job attachment files in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/attachments/files",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple job attachment files in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/attachments/files",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple job attachment files in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/attachments/files",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
