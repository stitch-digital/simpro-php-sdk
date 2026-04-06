<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\Attachments\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteContractorInvoiceAttachmentFileRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorInvoiceId,
        private readonly int|string $fileId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractorInvoices/{$this->contractorInvoiceId}/attachments/files/{$this->fileId}";
    }
}
