# Recurring Invoice Cost Centers Resource

A read-only list of every cost centre across every recurring invoice template in a company, each with the template and section it belongs to. Use it to find which templates charge against a given Simpro cost centre without walking each template's sections.

For the cost centres *within* one template (with create, update and delete), see [Recurring Invoice Cost Centers](./recurring-invoices/cost-centers-resource.md).

## Listing

```php
$lines = $connector->recurringInvoiceCostCenters(companyId: 0)->list()->all();

foreach ($lines as $line) {
    echo "Template {$line->recurringInvoice->id} ({$line->recurringInvoice->name}) ";
    echo "section {$line->section->id} charges cost centre {$line->costCenter->id} {$line->costCenter->name}\n";
}
```

### Filtering

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Every template line on cost centre 458
$lines = $connector->recurringInvoiceCostCenters(companyId: 0)->list()
    ->search(Search::make()->column('CostCenter.ID')->equals(458))
    ->all();

// Or as a plain query filter
$lines = $connector->recurringInvoiceCostCenters(companyId: 0)->list(['RecurringInvoice.ID' => 501])->all();
```

## Response Structure

### RecurringInvoiceCostCenterListItem

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | The cost centre line's ID within its template |
| `costCenter` | `?Reference` | The Simpro cost centre (`id`, `name`) |
| `name` | `?string` | |
| `recurringInvoice` | `?RecurringInvoiceCostCenterReference` | `id`, `type` (`RecurringInvoiceType`), `name` |
| `section` | `?Reference` | |
| `dateModified` | `?DateTimeImmutable` | |
| `href` | `?string` | The nested endpoint path for this line |
