<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Employees;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Files\CreateEmployeeAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Files\DeleteEmployeeAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Files\GetEmployeeAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Files\ListEmployeeAttachmentFilesRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Files\UpdateEmployeeAttachmentFileRequest;

/**
 * Resource for managing employee attachment files.
 *
 * @property AbstractSimproConnector $connector
 */
final class EmployeeAttachmentFileResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $employeeId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment files for this employee.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListEmployeeAttachmentFilesRequest($this->companyId, $this->employeeId);

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
        $request = new GetEmployeeAttachmentFileRequest($this->companyId, $this->employeeId, $fileId);

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
        $request = new CreateEmployeeAttachmentFileRequest($this->companyId, $this->employeeId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment file.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $fileId, array $data): Response
    {
        $request = new UpdateEmployeeAttachmentFileRequest($this->companyId, $this->employeeId, $fileId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment file.
     */
    public function delete(int|string $fileId): Response
    {
        $request = new DeleteEmployeeAttachmentFileRequest($this->companyId, $this->employeeId, $fileId);

        return $this->connector->send($request);
    }

    /**
     * Create multiple employee attachment files in a single request.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkCreate(array $data): BulkResponse
    {
        $request = new BulkCreateRequest(
            "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/attachments/files",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Update multiple employee attachment files in a single request.
     *
     * Each item in the data array must include an 'ID' key.
     *
     * @param  array<int, array<string, mixed>>  $data
     */
    public function bulkUpdate(array $data): BulkResponse
    {
        $request = new BulkUpdateRequest(
            "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/attachments/files",
            $data,
        );

        return $this->connector->send($request)->dto();
    }

    /**
     * Delete multiple employee attachment files in a single request.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, string>
     */
    public function bulkDelete(array $ids): array
    {
        $request = new BulkDeleteRequest(
            "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/attachments/files",
            $ids,
        );

        return $this->connector->send($request)->dto();
    }
}
