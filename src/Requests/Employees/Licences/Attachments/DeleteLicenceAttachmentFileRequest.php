<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Employees\Licences\Attachments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteLicenceAttachmentFileRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $employeeId,
        private readonly int|string $licenceId,
        private readonly int|string $fileId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/licences/{$this->licenceId}/attachments/files/{$this->fileId}";
    }
}
