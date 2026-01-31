<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Employees;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\Attachments\CreateLicenceAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\Attachments\DeleteLicenceAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\Attachments\GetLicenceAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\Attachments\ListLicenceAttachmentFilesRequest;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\Attachments\UpdateLicenceAttachmentFileRequest;

/**
 * Resource for managing licence attachment files.
 *
 * @property AbstractSimproConnector $connector
 */
final class LicenceAttachmentFileResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $employeeId,
        private readonly int|string $licenceId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment files for this licence.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListLicenceAttachmentFilesRequest($this->companyId, $this->employeeId, $this->licenceId);

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
        $request = new GetLicenceAttachmentFileRequest($this->companyId, $this->employeeId, $this->licenceId, $fileId);

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
        $request = new CreateLicenceAttachmentFileRequest($this->companyId, $this->employeeId, $this->licenceId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment file.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $fileId, array $data): Response
    {
        $request = new UpdateLicenceAttachmentFileRequest($this->companyId, $this->employeeId, $this->licenceId, $fileId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment file.
     */
    public function delete(int|string $fileId): Response
    {
        $request = new DeleteLicenceAttachmentFileRequest($this->companyId, $this->employeeId, $this->licenceId, $fileId);

        return $this->connector->send($request);
    }
}
