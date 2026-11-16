<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\RecurringInvoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoice;

/**
 * Request to list recurring invoices with every column the detail endpoint returns.
 *
 * Uses display=all and the full columns list so each item hydrates a RecurringInvoice DTO.
 */
final class ListRecurringInvoicesDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/recurringInvoices/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'display' => 'all',
            'columns' => implode(',', [
                'ID',
                'Type',
                'Customer',
                'CustomerContract',
                'CustomerContact',
                'AdditionalContacts',
                'Site',
                'SiteContact',
                'RequestNo',
                'Name',
                'OrderNo',
                'Description',
                'Notes',
                'RecurringStartDate',
                'NextRecurringDate',
                'RecurringQuantity',
                'InvoiceDatePeriod',
                'RecurringFrequency',
                'Tags',
                'PaymentTermID',
                'PaymentTerms',
                'Salesperson',
                'ProjectManager',
                'Technicians',
                'PricingTier',
                'LaborOverhead',
                'LaborType',
                'LaborFitTime',
                'ItemTaxCode',
                'LabourTaxCode',
                'MaterialMarkup',
                'InflationRate',
                'InflationAnniversaryDate',
                'DateModified',
                'Total',
                'Totals',
                'CustomFields',
                'Archived',
                'Removed',
            ]),
        ];
    }

    /**
     * @return array<RecurringInvoice>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => RecurringInvoice::fromArray($item),
            $response->json()
        );
    }
}
