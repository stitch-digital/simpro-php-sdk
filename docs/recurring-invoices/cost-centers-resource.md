# Recurring Invoice Cost Centers Resource

> [Recurring Invoices](../recurring-invoices-resource.md) > [Sections](./sections-resource.md) > Cost Centers

A cost centre on a template section names the Simpro cost centre each line is charged against and carries the line items. A template that charges two nominals (say, monitoring and maintenance) has two cost centres.

## Navigation

```php
$connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenters()
```

## Listing Cost Centers

```php
$costCenters = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenters()
    ->list()
    ->all();

foreach ($costCenters as $costCenter) {
    echo "{$costCenter->id}: {$costCenter->costCenter->name} {$costCenter->total->incTax}\n";
}
```

## Getting a Single Cost Center

```php
$costCenter = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenters()
    ->get(costCenterId: 7001);

echo "{$costCenter->name}: locked={$costCenter->itemsLocked}\n";
```

## Creating a Cost Center

`CostCenter` (the Simpro cost centre ID) is required.

```php
$costCenterId = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenters()
    ->create([
        'CostCenter' => 458,
        'Name' => 'Monitoring',
        'Description' => '<p>Qolsys monitoring</p>',
        'DisplayOrder' => 1,
    ]);
```

| Field | Type | Notes |
|-------|------|-------|
| `CostCenter` | `int` | Required. Simpro cost centre ID |
| `Name`, `Description`, `Notes`, `OrderNo` | `string` | Description and Notes accept HTML |
| `DisplayOrder` | `int` | |
| `Total` | `{ExTax, IncTax, TaxCode}` | Overrides the computed total; `TaxCode` is a tax code ID |
| `ItemsLocked` | `bool` | `PATCH` only |

## Updating a Cost Center

```php
$response = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenters()
    ->update(costCenterId: 7001, data: ['ItemsLocked' => true]);
```

## Deleting a Cost Center

```php
$response = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenters()
    ->delete(costCenterId: 7001);
```

## Response Structure

### CostCenterListItem

| Property | Type |
|----------|------|
| `id` | `int` |
| `costCenter` | `?Reference` |
| `recurringId` | `?int` |
| `name` | `?string` |
| `displayOrder` | `?int` |
| `total` | `?Money` (`exTax`, `tax`, `incTax`, `taxCode`) |

### CostCenter

| Property | Type |
|----------|------|
| `id` | `int` |
| `costCenter` | `?Reference` |
| `recurringId` | `?int` |
| `name`, `header` | `?string` |
| `site` | `?Reference` |
| `description`, `notes`, `orderNo` | `?string` |
| `displayOrder` | `?int` |
| `itemsLocked`, `revisedLocked`, `revizedLocked` | `?bool` | `revisedLocked` is Simpro's deprecated spelling
| `total` | `?Money` |
| `totals` | `?JobTotals` |
| `dateModified` | `?DateTimeImmutable` |

## Nested Resources

```php
$costCenter = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenter(7001);

$costCenter->oneOffs();      // see one-offs-resource.md
$costCenter->serviceFees();  // see service-fees-resource.md
$costCenter->catalogs();     // see catalogs-resource.md
$costCenter->labor();        // see labor-resource.md
$costCenter->prebuilds();    // see prebuilds-resource.md
```
