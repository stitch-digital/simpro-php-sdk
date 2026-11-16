# Recurring Invoice Labor Resource

> [Recurring Invoices](../recurring-invoices-resource.md) > [Sections](./sections-resource.md) > [Cost Centers](./cost-centers-resource.md) > Labor

A labor line prices hours of a labor rate on the template.

## Navigation

```php
$connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->labor()
```

## Listing, Getting, Creating, Updating and Deleting

```php
$labor = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->labor();

$items = $labor->list()->all();
$item = $labor->get(laborId: 8401);

$laborId = $labor->create([
    'LaborType' => 4,           // required: labor rate ID
    'LaborRate' => 45.00,
    'SalesTaxCode' => 6,
    'IncomeAccount' => 40,
    'Total' => ['Qty' => 1],    // required: hours
]);

$labor->update(laborId: $laborId, data: ['LaborRate' => 48.00]);
$labor->delete(laborId: $laborId);
```

Optional create and update fields: `LaborMarkup`, `Discount`, `SellPriceExDiscount`, `SellPriceIncDiscount`.

## Response Structure

### LaborListItem

| Property | Type |
|----------|------|
| `id` | `int` |
| `laborType` | `?Reference` |
| `discount` | `?float` |
| `sellPrice` | `?ItemSellPrice` |
| `total` | `?ItemTotal` |

### LaborItem

Adds to the list item: `laborRate`, `laborMarkup`, `salesTaxCode` (`?TaxCode`), `projectedTime`, `ticked` (`?bool`), `dateModified` and `incomeAccount` (`?IncomeAccount`).
