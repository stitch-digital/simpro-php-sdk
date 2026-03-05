<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\Contractors;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Employees\Attachments\AttachmentFolder;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Folders\CreateContractorAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Folders\DeleteContractorAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Folders\GetContractorAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Folders\ListContractorAttachmentFoldersRequest;
use Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Folders\UpdateContractorAttachmentFolderRequest;

/**
 * @property AbstractSimproConnector $connector
 */
final class ContractorAttachmentFolderResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $contractorId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment folders for this contractor.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorAttachmentFoldersRequest($this->companyId, $this->contractorId);

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
        $request = new GetContractorAttachmentFolderRequest($this->companyId, $this->contractorId, $folderId);

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
        $request = new CreateContractorAttachmentFolderRequest($this->companyId, $this->contractorId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment folder.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $folderId, array $data): Response
    {
        $request = new UpdateContractorAttachmentFolderRequest($this->companyId, $this->contractorId, $folderId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment folder.
     */
    public function delete(int|string $folderId): Response
    {
        $request = new DeleteContractorAttachmentFolderRequest($this->companyId, $this->contractorId, $folderId);

        return $this->connector->send($request);
    }
}
