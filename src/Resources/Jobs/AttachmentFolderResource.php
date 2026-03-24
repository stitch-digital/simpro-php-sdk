<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFolder;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Folders\CreateAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Folders\DeleteAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Folders\GetAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Folders\ListAttachmentFoldersRequest;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Folders\UpdateAttachmentFolderRequest;

/**
 * Resource for managing job attachment folders.
 *
 * @property AbstractSimproConnector $connector
 */
final class AttachmentFolderResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $jobId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment folders for this job.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListAttachmentFoldersRequest($this->companyId, $this->jobId);

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
        $request = new GetAttachmentFolderRequest($this->companyId, $this->jobId, $folderId);

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
        $request = new CreateAttachmentFolderRequest($this->companyId, $this->jobId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment folder.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $folderId, array $data): Response
    {
        $request = new UpdateAttachmentFolderRequest($this->companyId, $this->jobId, $folderId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment folder.
     */
    public function delete(int|string $folderId): Response
    {
        $request = new DeleteAttachmentFolderRequest($this->companyId, $this->jobId, $folderId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple job attachment folders in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/attachments/folders",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple job attachment folders in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/attachments/folders",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple job attachment folders in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/jobs/{$this->jobId}/attachments/folders",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
