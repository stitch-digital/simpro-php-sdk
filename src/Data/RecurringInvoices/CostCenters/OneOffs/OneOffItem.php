<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs;

use DateTimeImmutable;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\Common\IncomeAccount;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\TaxCode;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemSellPrice;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemTotal;

/**
 * A one-off line on a recurring invoice cost center: an ad hoc material or labour charge with its own cost, markup and sell price.
 */
final readonly class OneOffItem
{
    public function __construct(
        public int $id,
        public ?OneOffType $type,
        public ?bool $billable,
        public ?BillableStatus $billableStatus,
        public ?int $inventoryItem,
        public ?string $partNo,
        public ?Reference $vendor,
        public ?string $description,
        public ?float $estimatedCost,
        public ?float $markup,
        public ?float $laborCost,
        public ?float $estimatedTime,
        public ?TaxCode $salesTaxCode,
        public ?float $actualCost,
        public ?DateTimeImmutable $actualCostDate,
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
            type: isset($data['Type']) ? OneOffType::tryFrom($data['Type']) : null,
            billable: isset($data['Billable']) ? (bool) $data['Billable'] : null,
            billableStatus: isset($data['BillableStatus']) ? BillableStatus::tryFrom($data['BillableStatus']) : null,
            inventoryItem: isset($data['InventoryItem']) ? (int) $data['InventoryItem'] : null,
            partNo: $data['PartNo'] ?? null,
            vendor: ! empty($data['Vendor']) && is_array($data['Vendor']) ? Reference::fromArray($data['Vendor']) : null,
            description: $data['Description'] ?? null,
            estimatedCost: isset($data['EstimatedCost']) ? (float) $data['EstimatedCost'] : null,
            markup: isset($data['Markup']) ? (float) $data['Markup'] : null,
            laborCost: isset($data['LaborCost']) ? (float) $data['LaborCost'] : null,
            estimatedTime: isset($data['EstimatedTime']) ? (float) $data['EstimatedTime'] : null,
            salesTaxCode: ! empty($data['SalesTaxCode']) && is_array($data['SalesTaxCode']) ? TaxCode::fromArray($data['SalesTaxCode']) : null,
            actualCost: isset($data['ActualCost']) ? (float) $data['ActualCost'] : null,
            actualCostDate: ! empty($data['ActualCostDate']) ? new DateTimeImmutable($data['ActualCostDate']) : null,
            displayOrder: isset($data['DisplayOrder']) ? (int) $data['DisplayOrder'] : null,
            dateModified: ! empty($data['DateModified']) ? new DateTimeImmutable($data['DateModified']) : null,
            discount: isset($data['Discount']) ? (float) $data['Discount'] : null,
            sellPrice: ! empty($data['SellPrice']) && is_array($data['SellPrice']) ? ItemSellPrice::fromArray($data['SellPrice']) : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? ItemTotal::fromArray($data['Total']) : null,
            incomeAccount: ! empty($data['IncomeAccount']) && is_array($data['IncomeAccount']) ? IncomeAccount::fromArray($data['IncomeAccount']) : null,
        );
    }
}
