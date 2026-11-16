# Recurring Invoice Sections Resource

> [Recurring Invoices](../recurring-invoices-resource.md) > Sections

A section groups a template's cost centres. Only Project-type recurring invoices carry a section name and description; a Service template's section is usually a single unnamed one.

## Navigation

```php
$connector->recurringInvoices(companyId: 0)->recurringInvoice(501)->sections()
```

## Listing Sections

```php
$sections = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->sections()
    ->list()
    ->all();

foreach ($sections as $section) {
    echo "{$section->id}: {$section->name} (order {$section->displayOrder})\n";
}
```

## Getting a Single Section

```php
$section = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->sections()
    ->get(sectionId: 61);
```

## Creating a Section

```php
$sectionId = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->sections()
    ->create([
        'Name' => 'Monthly charges',          // Project templates only
        'Description' => 'Subscription and maintenance',
        'DisplayOrder' => 1,
    ]);
```

## Updating a Section

```php
$response = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->sections()
    ->update(sectionId: 61, data: ['DisplayOrder' => 2]);
```

## Deleting a Section

```php
$response = $connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->sections()
    ->delete(sectionId: 61);
```

## Response Structure

### SectionListItem

| Property | Type |
|----------|------|
| `id` | `int` |
| `name` | `?string` |
| `description` | `?string` |
| `displayOrder` | `?int` |

### Section

Adds `dateModified` (`?DateTimeImmutable`) to the list item's properties.

## Nested Resources

```php
$connector->recurringInvoices(companyId: 0)
    ->recurringInvoice(501)
    ->section(61)
    ->costCenters()
    ->list();
```

See [Cost Centers](./cost-centers-resource.md).
