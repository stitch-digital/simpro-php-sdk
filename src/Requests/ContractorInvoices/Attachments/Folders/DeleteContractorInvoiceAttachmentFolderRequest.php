<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Folders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteContractorInvoiceAttachmentFolderRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorInvoiceId,
        private readonly int|string $folderId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractorInvoices/{$this->contractorInvoiceId}/attachments/folders/{$this->folderId}";
    }
}
