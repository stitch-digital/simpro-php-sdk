<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Jobs;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFile;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
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
        private readonly int|string $companyId,
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
}
