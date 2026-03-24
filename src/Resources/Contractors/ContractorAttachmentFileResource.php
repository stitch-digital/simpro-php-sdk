<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Contractors;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Files\CreateContractorAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Files\DeleteContractorAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Files\GetContractorAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Files\ListContractorAttachmentFilesRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Files\UpdateContractorAttachmentFileRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class ContractorAttachmentFileResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $contractorId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment files for this contractor.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorAttachmentFilesRequest($this->companyId, $this->contractorId);

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
    public function get(int|string $fileId): Attachment
    {
        $request = new GetContractorAttachmentFileRequest($this->companyId, $this->contractorId, $fileId);

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
        $request = new CreateContractorAttachmentFileRequest($this->companyId, $this->contractorId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment file.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $fileId, array $data): Response
    {
        $request = new UpdateContractorAttachmentFileRequest($this->companyId, $this->contractorId, $fileId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment file.
     */
    public function delete(int|string $fileId): Response
    {
        $request = new DeleteContractorAttachmentFileRequest($this->companyId, $this->contractorId, $fileId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple contractor attachment files in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/attachments/files",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple contractor attachment files in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/attachments/files",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple contractor attachment files in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/attachments/files",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
