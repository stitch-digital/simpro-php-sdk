<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Folders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Employees\Attachments\AttachmentFolder;

final class GetContractorAttachmentFolderRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorId,
        private readonly int|string $folderId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/attachments/folders/{$this->folderId}";
    }

    public function createDtoFromResponse(Response $response): AttachmentFolder
    {
        return AttachmentFolder::fromArray($response->json());
    }
}
