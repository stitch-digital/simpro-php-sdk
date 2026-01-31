<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Folders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteEmployeeAttachmentFolderRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $employeeId,
        private readonly int|string $folderId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/attachments/folders/{$this->folderId}";
    }
}
