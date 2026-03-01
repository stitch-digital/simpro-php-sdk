# Job Cost Centers Resource

The Job Cost Centers resource provides read-only access to cost centers across all jobs in your Simpro environment. This is a top-level endpoint that returns cost centers without needing to navigate through individual job/section hierarchies.

## Basic Usage

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://your-instance.simprocloud.com',
    apiKey: 'your-api-key'
);

// Access job cost centers for a company
$costCenters = $connector->jobCostCenters(companyId: 0);
```

## Available Methods

### List Job Cost Centers

```php
// List all cost centers across all jobs
$costCenters = $connector->jobCostCenters(0)->list()->all();

foreach ($costCenters as $costCenter) {
    echo "{$costCenter->id}: {$costCenter->name} (Job: {$costCenter->job?->name})\n";
}

// With filtering
$costCenters = $connector->jobCostCenters(0)->list([
    'Job.ID' => 1001,
])->all();

// With search
use Simpro\PhpSdk\Simpro\Query\Search;

$costCenters = $connector->jobCostCenters(0)->list()
    ->search(Search::make()->column('Name')->find('Labour'))
    ->orderBy('Name')
    ->all();
```

## DTOs

### JobCostCenterListItem

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Cost center ID |
| `costCenter` | `?Reference` | Cost center reference (ID, Name) |
| `name` | `?string` | Cost center name |
| `job` | `?JobCostCenterJob` | Parent job details |
| `section` | `?Reference` | Section reference (ID, Name) |
| `dateModified` | `?DateTimeImmutable` | Last modification date |
| `href` | `?string` | API link to this cost center |

### JobCostCenterJob

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Job ID |
| `type` | `?string` | Job type (e.g., "Project", "Service") |
| `name` | `?string` | Job name |
| `stage` | `?string` | Job stage |
| `status` | `?string` | Job status |

## Examples

### Find Cost Centers for a Specific Job

```php
$costCenters = $connector->jobCostCenters(0)->list()
    ->where('Job.ID', '=', 1001)
    ->all();

foreach ($costCenters as $cc) {
    echo "{$cc->costCenter?->name}: {$cc->name}\n";
}
```

### Get All Labour Cost Centers

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$labourCostCenters = $connector->jobCostCenters(0)->list()
    ->search(Search::make()->column('Name')->find('Labour'))
    ->all();
```

### Find Recently Modified Cost Centers

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$recent = $connector->jobCostCenters(0)->list()
    ->search(Search::make()->column('DateModified')->greaterThanOrEqual('2026-01-01'))
    ->orderByDesc('DateModified')
    ->all();

foreach ($recent as $cc) {
    echo "{$cc->name} - Modified: {$cc->dateModified?->format('Y-m-d H:i')}\n";
}
```

## Pagination

Job cost centers support pagination through the QueryBuilder:

```php
// Iterate through all pages
foreach ($connector->jobCostCenters(0)->list()->items() as $costCenter) {
    echo "{$costCenter->id}: {$costCenter->name}\n";
}

// Get first page only
$firstPage = $connector->jobCostCenters(0)->list()->first();
```

## Limitations

The Job Cost Centers resource is **read-only** at the top level. To manage cost centers within a specific job, use the Jobs resource's nested cost center endpoints.
