<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Scopes\ContractorInvoices;

use Simpro\PhpSdk\Simpro\Connectors\AbstractSimproConnector;
use Simpro\PhpSdk\Simpro\Resources\ContractorInvoices\ContractorInvoiceAttachmentFileResource;
use Simpro\PhpSdk\Simpro\Resources\ContractorInvoices\ContractorInvoiceAttachmentFolderResource;
use Simpro\PhpSdk\Simpro\Resources\ContractorInvoices\ContractorInvoiceCustomFieldResource;
use Simpro\PhpSdk\Simpro\Scopes\AbstractScope;

/**
 * Scope for a specific contractor invoice, providing access to nested resources.
 *
 * @example
 * // Access attachment files
 * $connector->contractorInvoices(companyId: 0)->contractorInvoice(contractorInvoiceId: 123)->attachmentFiles()->list();
 *
 * // Access attachment folders
 * $connector->contractorInvoices(companyId: 0)->contractorInvoice(contractorInvoiceId: 123)->attachmentFolders()->list();
 *
 * // Access custom fields
 * $connector->contractorInvoices(companyId: 0)->contractorInvoice(contractorInvoiceId: 123)->customFields()->list();
 */
final class ContractorInvoiceScope extends AbstractScope
{
    public function __construct(
        AbstractSimproConnector $connector,
        int $companyId,
        private readonly int|string $contractorInvoiceId,
    ) {
        parent::__construct($connector, $companyId);
    }

    /**
     * Access attachment files for this contractor invoice.
     */
    public function attachmentFiles(): ContractorInvoiceAttachmentFileResource
    {
        return new ContractorInvoiceAttachmentFileResource($this->connector, $this->companyId, $this->contractorInvoiceId);
    }

    /**
     * Access attachment folders for this contractor invoice.
     */
    public function attachmentFolders(): ContractorInvoiceAttachmentFolderResource
    {
        return new ContractorInvoiceAttachmentFolderResource($this->connector, $this->companyId, $this->contractorInvoiceId);
    }

    /**
     * Access custom fields for this contractor invoice.
     */
    public function customFields(): ContractorInvoiceCustomFieldResource
    {
        return new ContractorInvoiceCustomFieldResource($this->connector, $this->companyId, $this->contractorInvoiceId);
    }
}
