<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Folders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Employees\Attachments\AttachmentFolder;

final class GetEmployeeAttachmentFolderRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $employeeId,
        private readonly int|string $folderId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/attachments/folders/{$this->folderId}";
    }

    public function createDtoFromResponse(Response $response): AttachmentFolder
    {
        return AttachmentFolder::fromResponse($response);
    }
}
