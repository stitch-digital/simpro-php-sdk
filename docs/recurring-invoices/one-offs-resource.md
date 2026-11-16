# Recurring Invoice One-Offs Resource

> [Recurring Invoices](../recurring-invoices-resource.md) > [Sections](./sections-resource.md) > [Cost Centers](./cost-centers-resource.md) > One-Offs

A one-off is an ad hoc line that is not a catalog item: a described charge with its own estimated cost, markup, sell price, tax code and income account. It is the simplest way to put a fixed subscription charge on a template.

## Navigation

```php
$connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->oneOffs()
```

## Listing One-Offs

```php
$items = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->oneOffs()
    ->list()
    ->all();

foreach ($items as $item) {
    echo "{$item->id}: {$item->description} x{$item->total->qty} = {$item->total->amount->incTax}\n";
}
```

## Getting a Single One-Off

```php
$item = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->oneOffs()
    ->get(oneOffId: 8101);
```

## Creating a One-Off

`Type`, `Description` and `Total.Qty` are required. Note the spelling: the labour type is `Labour` here (it is `Labor` on jobs).

```php
$oneOffId = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->oneOffs()
    ->create([
        'Type' => 'Material',
        'Description' => 'Qolsys subscription',
        'BillableStatus' => 'Billable',
        'EstimatedCost' => 12.00,
        'SellPriceExDiscount' => 18.75,
        'SalesTaxCode' => 6,
        'IncomeAccount' => 40,
        'Total' => ['Qty' => 1],
    ]);
```

| Field | Type | Notes |
|-------|------|-------|
| `Type` | `Material\|Labour` | Required on create; not updatable |
| `Description` | `string` | Required |
| `Total.Qty` | `float` | Required |
| `BillableStatus` | `Billable\|NonBillable\|NonBillableIncludeCosts` | |
| `EstimatedCost` | `float` | Cost before markup |
| `Markup` | `float` | |
| `SellPrice`, `SellPriceExDiscount`, `SellPriceIncDiscount` | `float` | |
| `Discount` | `float` | |
| `SalesTaxCode` | `int` | Tax code ID |
| `IncomeAccount` | `int` | Chart of accounts ID |
| `EstimatedTime` | `float` | |
| `ActualCost`, `ActualCostDate` | `float`, `YYYY-MM-DD` | |
| `InventoryItem`, `PartNo`, `Vendor` | `bool`, `string`, `int` | Material inventory items only |

## Updating a One-Off

```php
$response = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->oneOffs()
    ->update(oneOffId: 8101, data: ['SellPriceExDiscount' => 19.50]);
```

## Deleting a One-Off

```php
$response = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->oneOffs()
    ->delete(oneOffId: 8101);
```

## Response Structure

### OneOffListItem

| Property | Type |
|----------|------|
| `id` | `int` |
| `type` | `?OneOffType` (`Material`, `Labour`) |
| `billableStatus` | `?BillableStatus` |
| `description` | `?string` |
| `discount` | `?float` |
| `sellPrice` | `?ItemSellPrice` |
| `total` | `?ItemTotal` |

### OneOffItem

Adds to the list item: `billable` (deprecated `?bool`), `inventoryItem` (`?int`), `partNo`, `vendor` (`?Reference`), `estimatedCost`, `markup`, `laborCost`, `estimatedTime`, `salesTaxCode` (`?TaxCode`), `actualCost`, `actualCostDate`, `displayOrder`, `dateModified` and `incomeAccount` (`?IncomeAccount`).
