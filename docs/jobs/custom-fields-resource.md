# Job Custom Fields Resource

> [Jobs](../jobs-resource.md) > Custom Fields

Custom fields allow you to read and update custom field values on jobs.

## Navigation

```php
// Access custom fields for a specific job
$connector->jobs(companyId: 0)->job(jobId: 123)->customFields()
```

## Listing Custom Fields

```php
$customFields = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->customFields()
    ->list()
    ->all();

foreach ($customFields as $field) {
    echo "{$field->name}: {$field->value}\n";
}
```

## Getting a Single Custom Field

```php
$field = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->customFields()
    ->get(customFieldId: 456);

echo "Field: {$field->name} = {$field->value}\n";
```

## Updating a Custom Field

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->customFields()
    ->update(customFieldId: 456, data: [
        'Value' => 'New value',
    ]);

if ($response->successful()) {
    echo "Custom field updated\n";
}
```

## Response Structure

### CustomField

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Custom field ID |
| `name` | `?string` | Field name |
| `value` | `mixed` | Field value |
| `type` | `?string` | Field type (text, number, date, etc.) |
