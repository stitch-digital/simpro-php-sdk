<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors\Attachments\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;

final class ListContractorAttachmentFilesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/attachments/files/";
    }

    /**
     * @return array<Attachment>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => Attachment::fromArray($item),
            $data
        );
    }
}
