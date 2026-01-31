<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Employees;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Employees\Attachments\AttachmentFolder;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Folders\CreateEmployeeAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Folders\DeleteEmployeeAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Folders\GetEmployeeAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Folders\ListEmployeeAttachmentFoldersRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Folders\UpdateEmployeeAttachmentFolderRequest;

/**
 * Resource for managing employee attachment folders.
 *
 * @property AbstractSimproConnector $connector
 */
final class EmployeeAttachmentFolderResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $employeeId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment folders for this employee.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListEmployeeAttachmentFoldersRequest($this->companyId, $this->employeeId);

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
        $request = new GetEmployeeAttachmentFolderRequest($this->companyId, $this->employeeId, $folderId);

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
        $request = new CreateEmployeeAttachmentFolderRequest($this->companyId, $this->employeeId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment folder.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $folderId, array $data): Response
    {
        $request = new UpdateEmployeeAttachmentFolderRequest($this->companyId, $this->employeeId, $folderId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment folder.
     */
    public function delete(int|string $folderId): Response
    {
        $request = new DeleteEmployeeAttachmentFolderRequest($this->companyId, $this->employeeId, $folderId);

        return $this->connector->send($request);
    }
}
