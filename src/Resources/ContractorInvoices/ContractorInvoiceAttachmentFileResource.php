<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources\ContractorInvoices;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFile;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Files\CreateContractorInvoiceAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Files\DeleteContractorInvoiceAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Files\GetContractorInvoiceAttachmentFileRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Files\ListContractorInvoiceAttachmentFilesRequest;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Files\UpdateContractorInvoiceAttachmentFileRequest;

/**
 * Resource for managing contractor invoice attachment files.
 *
 * @property AbstractSimproConnector $connector
 */
final class ContractorInvoiceAttachmentFileResource extends BaseResource
{
    public function __construct(
        AbstractSimproConnector $connector,
        private readonly int $companyId,
        private readonly int|string $contractorInvoiceId,
    ) {
        parent::__construct($connector);
    }

    /**
     * List all attachment files for this contractor invoice.
     *
     * @param  array<string, mixed>  $filters  Initial filters to apply
     */
    public function list(array $filters = []): QueryBuilder
    {
        $request = new ListContractorInvoiceAttachmentFilesRequest($this->companyId, $this->contractorInvoiceId);

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
        $request = new GetContractorInvoiceAttachmentFileRequest($this->companyId, $this->contractorInvoiceId, $fileId);

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
        $request = new CreateContractorInvoiceAttachmentFileRequest($this->companyId, $this->contractorInvoiceId, $data);

        return $this->connector->send($request)->dto();
    }

    /**
     * Update an existing attachment file.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $fileId, array $data): Response
    {
        $request = new UpdateContractorInvoiceAttachmentFileRequest($this->companyId, $this->contractorInvoiceId, $fileId, $data);

        return $this->connector->send($request);
    }

    /**
     * Delete an attachment file.
     */
    public function delete(int|string $fileId): Response
    {
        $request = new DeleteContractorInvoiceAttachmentFileRequest($this->companyId, $this->contractorInvoiceId, $fileId);

        return $this->connector->send($request);
    }
}
