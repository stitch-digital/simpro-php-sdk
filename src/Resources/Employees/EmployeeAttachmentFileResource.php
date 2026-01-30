<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Employees;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
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
        private readonly int|string $companyId,
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
}
