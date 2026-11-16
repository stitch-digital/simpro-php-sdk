<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Catalogs;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\Common\IncomeAccount;
use Simpro\PhpSdk\Simpro\Data\Common\PartReference;
use Simpro\PhpSdk\Simpro\Data\Common\TaxCode;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemSellPrice;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemTotal;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemTotals;
use Simpro\PhpSdk\Simpro\Data\Setup\FitTime;

/**
 * A catalog (material) line on a recurring invoice cost center.
 */
final readonly class CatalogItem
{
    public function __construct(
        public int $id,
        public ?PartReference $catalog,
        public ?bool $billableItem,
        public ?BillableStatus $billableStatus,
        public ?float $estimatedTime,
        public ?FitTime $fitTime,
        public ?float $laborCost,
        public ?float $basePrice,
        public ?float $markup,
        public ?TaxCode $salesTaxCode,
        public ?ItemTotals $totals,
        public ?int $displayOrder,
        public ?DateTimeImmutable $dateModified,
        public ?float $discount,
        public ?ItemSellPrice $sellPrice,
        public ?ItemTotal $total,
        public ?IncomeAccount $incomeAccount,
    ) {}

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            catalog: ! empty($data['Catalog']) && is_array($data['Catalog']) ? PartReference::fromArray($data['Catalog']) : null,
            billableItem: isset($data['BillableItem']) ? (bool) $data['BillableItem'] : null,
            billableStatus: isset($data['BillableStatus']) ? BillableStatus::tryFrom($data['BillableStatus']) : null,
            estimatedTime: isset($data['EstimatedTime']) ? (float) $data['EstimatedTime'] : null,
            fitTime: ! empty($data['FitTime']) && is_array($data['FitTime']) ? FitTime::fromArray($data['FitTime']) : null,
            laborCost: isset($data['LaborCost']) ? (float) $data['LaborCost'] : null,
            basePrice: isset($data['BasePrice']) ? (float) $data['BasePrice'] : null,
            markup: isset($data['Markup']) ? (float) $data['Markup'] : null,
            salesTaxCode: ! empty($data['SalesTaxCode']) && is_array($data['SalesTaxCode']) ? TaxCode::fromArray($data['SalesTaxCode']) : null,
            totals: ! empty($data['Totals']) && is_array($data['Totals']) ? ItemTotals::fromArray($data['Totals']) : null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            discount: isset($data['Discount']) ? (float) $data['Discount'] : null,
            sellPrice: ! empty($data['SellPrice']) && is_array($data['SellPrice']) ? ItemSellPrice::fromArray($data['SellPrice']) : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? ItemTotal::fromArray($data['Total']) : null,
            incomeAccount: ! empty($data['IncomeAccount']) && is_array($data['IncomeAccount']) ? IncomeAccount::fromArray($data['IncomeAccount']) : null,
        );
    }
}
