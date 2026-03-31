<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ContractorJobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobListItem;

final class ListContractorJobsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractorJobs/";
    }

    /**
     * @return array<ContractorJobListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => ContractorJobListItem::fromArray($item),
            $data
        );
    }
}
