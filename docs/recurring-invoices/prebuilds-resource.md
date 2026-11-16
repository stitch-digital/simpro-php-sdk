# Recurring Invoice Prebuilds Resource

> [Recurring Invoices](../recurring-invoices-resource.md) > [Sections](./sections-resource.md) > [Cost Centers](./cost-centers-resource.md) > Prebuilds

A prebuild line prices a prebuild assembly on the template.

## Navigation

```php
$connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->prebuilds()
```

## Listing, Getting, Creating, Updating and Deleting

```php
$prebuilds = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->prebuilds();

$items = $prebuilds->list()->all();
$item = $prebuilds->get(prebuildId: 8501);

$prebuildId = $prebuilds->create([
    'Prebuild' => 310,          // required: prebuild ID
    'BasePrice' => 12.00,
    'SalesTaxCode' => 6,
    'IncomeAccount' => 40,
    'Total' => ['Qty' => 1],    // required
]);

$prebuilds->update(prebuildId: $prebuildId, data: ['Markup' => 60]);
$prebuilds->delete(prebuildId: $prebuildId);
```

Optional create and update fields: `BillableStatus`, `EstimatedTime`, `FitTime` (ID), `Markup`, `Discount`, `SellPrice`, `SellPriceExDiscount`, `SellPriceIncDiscount`.

## Response Structure

### PrebuildListItem

| Property | Type |
|----------|------|
| `id` | `int` |
| `prebuild` | `?PartReference` (`id`, `partNo`, `name`) |
| `billableStatus` | `?BillableStatus` |
| `discount` | `?float` |
| `sellPrice` | `?ItemSellPrice` |
| `total` | `?ItemTotal` |

### PrebuildItem

Adds to the list item: `billableItem` (deprecated `?bool`), `estimatedTime`, `fitTime` (`?FitTime`), `basePrice`, `markup`, `laborCost`, `salesTaxCode` (`?TaxCode`), `totals` (`?ItemTotals`), `displayOrder`, `dateModified` and `incomeAccount` (`?IncomeAccount`).
