# Job Sections Resource

> [Jobs](../jobs-resource.md) > Sections

Job sections divide a job into logical parts or phases. Each section can contain multiple cost centers.

## Navigation

```php
// Access sections for a specific job
$connector->jobs(companyId: 0)->job(jobId: 123)->sections()
```

## Listing Sections

```php
$sections = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->sections()
    ->list()
    ->all();

foreach ($sections as $section) {
    echo "{$section->id}: {$section->name}\n";
}
```

### With Filtering

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$sections = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->sections()
    ->list()
    ->search(Search::make()->column('Name')->find('Phase'))
    ->orderBy('Name')
    ->all();
```

## Getting a Single Section

```php
$section = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->sections()
    ->get(sectionId: 1);

echo "Section: {$section->name}\n";
```

## Creating a Section

```php
$sectionId = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->sections()
    ->create(data: [
        'Name' => 'Phase 1 - Demolition',
        'Description' => 'Initial demolition and site preparation',
    ]);

echo "Created section with ID: {$sectionId}\n";
```

## Updating a Section

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->sections()
    ->update(sectionId: 1, data: [
        'Name' => 'Phase 1 - Complete',
    ]);

if ($response->successful()) {
    echo "Section updated\n";
}
```

## Deleting a Section

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->sections()
    ->delete(sectionId: 1);

if ($response->successful()) {
    echo "Section deleted\n";
}
```

## Response Structure

### Section

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Section ID |
| `name` | `?string` | Section name |
| `description` | `?string` | Section description |
| `costCenters` | `?array` | Associated cost centers |

## Nested Resources

Sections provide access to cost centers:

```php
// Navigate to cost centers within a section
$costCenters = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenters()
    ->list()
    ->all();
```

See [Cost Centers](./cost-centers-resource.md) for full documentation.
