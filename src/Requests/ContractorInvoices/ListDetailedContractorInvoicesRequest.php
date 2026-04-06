<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\ContractorInvoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\ContractorInvoices\ContractorInvoice;

/**
 * List contractor invoices with full details.
 *
 * Uses the columns parameter to request all available fields,
 * returning full ContractorInvoice DTOs instead of list items.
 */
final class ListDetailedContractorInvoicesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    private const DETAILED_COLUMNS = [
        'ID',
        'ContractorJobs',
        'Contractor',
        'InvoiceNo',
        'DateIssued',
        'DueDate',
        'DatePaid',
        'Currency',
        'ExchangeRate',
        'Category',
        'Notes',
        'CostCenters',
        'Retentions',
        'Variances',
        'Total',
        'CustomFields',
        'DateModified',
        'CISDeduction',
        'RCTDeduction',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/contractorInvoices/";
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
     * @return array<ContractorInvoice>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => ContractorInvoice::fromArray($item),
            $data
        );
    }
}
