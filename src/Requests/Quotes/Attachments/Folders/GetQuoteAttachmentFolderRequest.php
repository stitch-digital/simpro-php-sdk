<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes\Attachments\Folders;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Jobs\Attachments\AttachmentFolder;

final class GetQuoteAttachmentFolderRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $folderId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/attachments/folders/{$this->folderId}";
    }

    public function createDtoFromResponse(Response $response): AttachmentFolder
    {
        return AttachmentFolder::fromResponse($response);
    }
}
