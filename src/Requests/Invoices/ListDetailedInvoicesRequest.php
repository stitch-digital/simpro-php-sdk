<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Invoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Invoices\Invoice;

/**
 * List invoices with full details.
 *
 * Uses the columns parameter to request all available fields,
 * returning full Invoice DTOs instead of list items.
 */
final class ListDetailedInvoicesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    private const DETAILED_COLUMNS = [
        'ID',
        'InternalID',
        'Type',
        'Customer',
        'Jobs',
        'RecurringInvoice',
        'DateIssued',
        'Period',
        'PaymentTermID',
        'PaymentTerms',
        'ProgressClaimNumber',
        'IsFinalClaim',
        'Stage',
        'PerItem',
        'OrderNo',
        'LatePaymentFee',
        'CISDeductionRate',
        'ExchangeRate',
        'AccountingCategory',
        'Status',
        'AutoAdjustStatus',
        'Description',
        'Notes',
        'Total',
        'IsRetainage',
        'Retainage',
        'RetainageRebate',
        'IsPaid',
        'DatePaid',
        'Currency',
        'DateCreated',
        'DateModified',
        'CustomFields',
        'CostCenters',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/invoices/";
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
     * @return array<Invoice>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => Invoice::fromArray($item),
            $data
        );
    }
}
