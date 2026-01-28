# Job Labor Resource

> [Jobs](../jobs-resource.md) > [Sections](./sections-resource.md) > [Cost Centers](./cost-centers-resource.md) > Labor

Labor items represent time/work to be performed within a cost center.

## Navigation

```php
// Access labor for a specific cost center
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()
```

## Listing Labor Items

```php
$laborItems = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()
    ->list()
    ->all();

foreach ($laborItems as $labor) {
    echo "{$labor->description}: {$labor->quantity} hours\n";
}
```

## Getting a Single Labor Item

```php
$labor = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()
    ->get(laborId: 99);
```

## Creating a Labor Item

```php
$laborId = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()
    ->create(data: [
        'Description' => 'Plumbing installation',
        'LaborRate' => ['ID' => 10],
        'Quantity' => 8,
    ]);

echo "Created labor item with ID: {$laborId}\n";
```

## Bulk Replace Labor Items

Replace all labor items in the cost center:

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()
    ->bulkReplace(data: [
        [
            'Description' => 'Demolition',
            'LaborRate' => ['ID' => 10],
            'Quantity' => 4,
        ],
        [
            'Description' => 'Installation',
            'LaborRate' => ['ID' => 10],
            'Quantity' => 8,
        ],
    ]);
```

## Updating a Labor Item

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()
    ->update(laborId: 99, data: [
        'Quantity' => 10,
    ]);
```

## Deleting a Labor Item

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()
    ->delete(laborId: 99);
```

## Response Structure

### LaborItem

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Labor item ID |
| `description` | `?string` | Description of work |
| `quantity` | `?float` | Hours/units |
| `laborRate` | `?LaborRate` | Associated labor rate |
| `sellPrice` | `?Money` | Sell price |
| `costPrice` | `?Money` | Cost price |

## Examples

### Calculate Total Labor Hours

```php
$laborItems = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->labor()
    ->list()
    ->all();

$totalHours = 0;
foreach ($laborItems as $labor) {
    $totalHours += $labor->quantity ?? 0;
}

echo "Total labor hours: {$totalHours}\n";
```
