<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ContractorJobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobDetail;

final class ListDetailedContractorJobsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    private const DETAILED_COLUMNS = [
        'ID',
        'ProjectType',
        'Contractor',
        'CreatedBy',
        'Status',
        'Description',
        'DateIssued',
        'DueDate',
        'ContractorSupplyMaterials',
        'Materials',
        'Currency',
        'ExchangeRate',
        'Labor',
        'TaxCode',
        'Retention',
        'Total',
        'CustomFields',
        'DateModified',
        '_href',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractorJobs/";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<ContractorJobDetail>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => ContractorJobDetail::fromArray($item),
            $data
        );
    }
}
