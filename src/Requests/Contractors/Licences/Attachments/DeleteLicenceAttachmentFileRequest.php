<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors\Licences\Attachments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteLicenceAttachmentFileRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorId,
        private readonly int|string $licenceId,
        private readonly int|string $fileId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/licences/{$this->licenceId}/attachments/files/{$this->fileId}";
    }
}
