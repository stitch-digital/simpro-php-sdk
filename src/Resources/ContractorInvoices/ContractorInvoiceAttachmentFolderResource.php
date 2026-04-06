<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\ContractorInvoices;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFolder;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Folders\CreateContractorInvoiceAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Folders\DeleteContractorInvoiceAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Folders\GetContractorInvoiceAttachmentFolderRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Folders\ListContractorInvoiceAttachmentFoldersRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Folders\UpdateContractorInvoiceAttachmentFolderRequest;

/**
 * Resource for managing contractor invoice attachment folders.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractorInvoiceAttachmentFolderResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $contractorInvoiceId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment folders for this contractor invoice.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorInvoiceAttachmentFoldersRequest($this->companyId, $this->contractorInvoiceId);

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
        $request = new GetContractorInvoiceAttachmentFolderRequest($this->companyId, $this->contractorInvoiceId, $folderId);

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
        $request = new CreateContractorInvoiceAttachmentFolderRequest($this->companyId, $this->contractorInvoiceId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment folder.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $folderId, array $data): Response
    {
        $request = new UpdateContractorInvoiceAttachmentFolderRequest($this->companyId, $this->contractorInvoiceId, $folderId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment folder.
     */
    public function delete(int|string $folderId): Response
    {
        $request = new DeleteContractorInvoiceAttachmentFolderRequest($this->companyId, $this->contractorInvoiceId, $folderId);

        return $this->connector->send($request);
    }
}
