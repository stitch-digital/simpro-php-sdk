<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;

final class GetEmployeeAttachmentFileRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $employeeId,
        private readonly int|string $fileId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/employees/{$this->employeeId}/attachments/files/{$this->fileId}";
    }

    public function createDtoFromResponse(Response $response): Attachment
    {
        return Attachment::fromArray($response->json());
    }
}
