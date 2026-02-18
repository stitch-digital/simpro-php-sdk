<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Quotes\Quote;

final class ListQuotesDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID', 'Type', 'Name', 'Site', 'Customer', 'AdditionalCustomers',
                'CustomerContact', 'AdditionalContacts', 'SiteContact',
                'ConvertedFromLead', 'Salesperson', 'ProjectManager',
                'Technicians', 'Technician', 'Status', 'Stage',
                'OrderNo', 'RequestNo', 'Description', 'Notes',
                'DateIssued', 'DueDate', 'DateApproved', 'ValidityDays',
                'IsClosed', 'ArchiveReason', 'CustomerStage', 'JobNo',
                'IsVariation', 'LinkedJobId', 'Forecast',
                'Total', 'Totals', 'Tags', 'AutoAdjustStatus',
                'CustomFields', 'STC', 'DateModified',
            ]),
        ];
    }

    /**
     * @return array<Quote>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(fn (array $item) => Quote::fromArray($item), $response->json());
    }
}
