<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteContractorAttachmentFileRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorId,
        private readonly int|string $fileId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/attachments/files/{$this->fileId}";
    }
}
