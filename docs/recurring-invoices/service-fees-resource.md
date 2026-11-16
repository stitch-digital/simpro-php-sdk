# Recurring Invoice Service Fees Resource

> [Recurring Invoices](../recurring-invoices-resource.md) > [Sections](./sections-resource.md) > [Cost Centers](./cost-centers-resource.md) > Service Fees

A service fee line references one of the service fees defined in Simpro setup and prices it on the template.

## Navigation

```php
$connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->serviceFees()
```

## Listing Service Fees

```php
$fees = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->serviceFees()
    ->list()
    ->all();

foreach ($fees as $fee) {
    echo "{$fee->id}: {$fee->serviceFee->name} = {$fee->total->amount->incTax}\n";
}
```

## Getting a Single Service Fee

```php
$fee = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->serviceFees()
    ->get(serviceFeeId: 8201);
```

## Creating a Service Fee

`ServiceFee` (the setup service fee ID) and `Total.Qty` are required.

```php
$serviceFeeId = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->serviceFees()
    ->create([
        'ServiceFee' => 33,
        'BasePrice' => 10.42,
        'SalesTaxCode' => 6,
        'IncomeAccount' => 40,
        'Total' => ['Qty' => 1],
    ]);
```

| Field | Type | Notes |
|-------|------|-------|
| `ServiceFee` | `int` | Required on create |
| `Total.Qty` | `float` | Required |
| `BillableStatus` | `Billable\|NonBillable\|NonBillableIncludeCosts` | |
| `FitTime` | `int` | Fit time ID |
| `BasePrice`, `Markup`, `Discount` | `float` | |
| `SellPrice`, `SellPriceExDiscount`, `SellPriceIncDiscount` | `float` | |
| `SalesTaxCode`, `IncomeAccount` | `int` | IDs |

## Updating a Service Fee

```php
$response = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->serviceFees()
    ->update(serviceFeeId: 8201, data: ['BasePrice' => 13.89]);
```

## Deleting a Service Fee

```php
$response = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001)
    ->serviceFees()
    ->delete(serviceFeeId: 8201);
```

## Response Structure

### ServiceFeeListItem

| Property | Type |
|----------|------|
| `id` | `int` |
| `serviceFee` | `?Reference` |
| `billableStatus` | `?BillableStatus` |
| `discount` | `?float` |
| `sellPrice` | `?ItemSellPrice` |
| `total` | `?ItemTotal` |

### ServiceFeeItem

Adds to the list item: `billableItem` (deprecated `?bool`), `fitTime` (`?FitTime`), `basePrice`, `markup`, `laborCost`, `salesTaxCode` (`?TaxCode`), `totals` (`?ItemTotals`), `displayOrder`, `dateModified` and `incomeAccount` (`?IncomeAccount`).
