# Recurring Invoice Nested Resources

> [Recurring Invoices](../recurring-invoices-resource.md)

The resources under a recurring invoice template, reached through the same scope-based fluent API as [Jobs](../jobs/README.md).

## Resource Hierarchy

```
Recurring Invoices
└── Sections
    └── Cost Centers
        ├── Catalogs
        ├── Labor
        ├── One-Offs
        ├── Prebuilds
        └── Service Fees
```

## Documentation Pages

| Resource | Documentation | Description |
|----------|---------------|-------------|
| Sections | [sections-resource.md](./sections-resource.md) | A template's sections (one is usual) |
| Cost Centers | [cost-centers-resource.md](./cost-centers-resource.md) | One per Simpro cost centre the template charges |
| Catalogs | [catalogs-resource.md](./catalogs-resource.md) | Catalog (material) line items |
| Labor | [labor-resource.md](./labor-resource.md) | Labor line items |
| One-Offs | [one-offs-resource.md](./one-offs-resource.md) | Ad hoc material or labour line items |
| Prebuilds | [prebuilds-resource.md](./prebuilds-resource.md) | Prebuild assemblies |
| Service Fees | [service-fees-resource.md](./service-fees-resource.md) | Service fee line items |

## Navigation

```php
$template = $connector->recurringInvoices(companyId: 0)->recurringInvoice(501);

// Level 2: sections
$template->sections()->list();

// Level 3: cost centers within a section
$template->section(61)->costCenters()->list();

// Level 4: line items within a cost center
$costCenter = $template->section(61)->costCenter(7001);
$costCenter->oneOffs()->list();
$costCenter->serviceFees()->list();
$costCenter->catalogs()->list();
$costCenter->labor()->list();
$costCenter->prebuilds()->list();
```

Every resource offers `list()`, `get()`, `create()`, `update()`, `delete()`, `bulkCreate()`, `bulkUpdate()` and `bulkDelete()`. Unlike job cost centres there is no `PUT` to replace a cost centre's items in one call.

## Shared line item types

Line item DTOs share a few value objects in `Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters`:

| Class | Properties |
|-------|------------|
| `ItemSellPrice` | `exTax`, `incTax`, `exDiscountExTax`, `exDiscountIncTax` |
| `ItemTotal` | `qty`, `amount` (`ItemAmount`: `exTax`, `incTax`) |
| `ItemTotals` | `priceEx`, `priceInc`, `estimatedTime` |

And from `Simpro\PhpSdk\Simpro\Data\Common`:

| Class | Properties |
|-------|------------|
| `BillableStatus` (enum) | `Billable`, `NonBillable`, `NonBillableIncludeCosts` |
| `IncomeAccount` | `id`, `name`, `number` |
| `PartReference` | `id`, `partNo`, `name` |
