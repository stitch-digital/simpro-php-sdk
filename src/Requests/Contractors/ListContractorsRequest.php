<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Contractors;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorListItem;

final class ListContractorsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractors/";
    }

    /**
     * @return array<ContractorListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => ContractorListItem::fromArray($item),
            $data
        );
    }
}
