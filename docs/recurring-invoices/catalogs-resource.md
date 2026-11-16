# Recurring Invoice Catalogs Resource

> [Recurring Invoices](../recurring-invoices-resource.md) > [Sections](./sections-resource.md) > [Cost Centers](./cost-centers-resource.md) > Catalogs

A catalog line prices a catalog (material) item on the template.

## Navigation

```php
$connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->catalogs()
```

## Listing, Getting, Creating, Updating and Deleting

```php
$catalogs = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->catalogs();

$items = $catalogs->list()->all();
$item = $catalogs->get(catalogId: 8301);

$catalogId = $catalogs->create([
    'Catalog' => 1200,          // required: catalog item ID
    'BasePrice' => 12.00,
    'Markup' => 56.25,
    'SalesTaxCode' => 6,
    'IncomeAccount' => 40,
    'Total' => ['Qty' => 1],    // required
]);

$catalogs->update(catalogId: $catalogId, data: ['Markup' => 60]);
$catalogs->delete(catalogId: $catalogId);
```

Optional create and update fields: `BillableStatus`, `EstimatedTime`, `FitTime` (ID), `Discount`, `SellPrice`, `SellPriceExDiscount`, `SellPriceIncDiscount`.

## Response Structure

### CatalogListItem

| Property | Type |
|----------|------|
| `id` | `int` |
| `catalog` | `?PartReference` (`id`, `partNo`, `name`) |
| `billableStatus` | `?BillableStatus` |
| `basePrice`, `markup`, `discount` | `?float` |
| `sellPrice` | `?ItemSellPrice` |
| `total` | `?ItemTotal` |

### CatalogItem

Adds to the list item: `billableItem` (deprecated `?bool`), `estimatedTime`, `fitTime` (`?FitTime`), `laborCost`, `salesTaxCode` (`?TaxCode`), `totals` (`?ItemTotals`), `displayOrder`, `dateModified` and `incomeAccount` (`?IncomeAccount`).
