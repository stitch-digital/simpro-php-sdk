<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors\CustomFields;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Common\CustomField;

final class ListContractorCustomFieldsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $contractorId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/{$this->contractorId}/customFields/";
    }

    /**
     * @return array<CustomField>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => CustomField::fromArray($item),
            $data
        );
    }
}
