# Jobs Resource

The Jobs resource provides full CRUD operations for managing jobs in your Simpro environment. Jobs are the primary work units representing projects, service calls, or tasks to be completed for customers.

## Listing Jobs

### Basic List

Returns all jobs with pagination support:

```php
$jobs = $connector->jobs(0)->list();

foreach ($jobs->items() as $job) {
    echo "{$job->id}: {$job->name} - {$job->customer} ({$job->status})\n";
}
```

### Filtering Jobs

Use the fluent search API or array-based filters:

#### Fluent Search API (Recommended)

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Search by name
$jobs = $connector->jobs(0)->list()
    ->search(Search::make()->column('Name')->find('Kitchen'))
    ->items();

// Search by customer
$jobs = $connector->jobs(0)->list()
    ->where('CustomerID', '=', 456)
    ->items();

// Search by status
$jobs = $connector->jobs(0)->list()
    ->where('Status', '=', 'In Progress')
    ->items();

// Search by stage
$jobs = $connector->jobs(0)->list()
    ->where('Stage', '=', 'Active')
    ->items();

// Search by site
$jobs = $connector->jobs(0)->list()
    ->where('SiteID', '=', 789)
    ->items();

// Find jobs within a date range
$jobs = $connector->jobs(0)->list()
    ->search(Search::make()->column('DateIssued')->between('2024-01-01', '2024-01-31'))
    ->orderByDesc('DateIssued')
    ->items();

// Multiple criteria with AND logic
$jobs = $connector->jobs(0)->list()
    ->search([
        Search::make()->column('Status')->equals('In Progress'),
        Search::make()->column('Type')->equals('Service'),
    ])
    ->matchAll()
    ->orderBy('DueDate')
    ->items();

// Multiple criteria with OR logic
$jobs = $connector->jobs(0)->list()
    ->search([
        Search::make()->column('Status')->equals('Pending'),
        Search::make()->column('Status')->equals('In Progress'),
    ])
    ->matchAny()
    ->items();
```

#### Array-Based Syntax

```php
// Search by name
$jobs = $connector->jobs(0)->list(['Name' => '%25Kitchen%25']);

// Filter by status
$jobs = $connector->jobs(0)->list(['Status' => 'In Progress']);

// Filter by type
$jobs = $connector->jobs(0)->list(['Type' => 'Service']);

// Order by date issued descending
$jobs = $connector->jobs(0)->list(['orderby' => '-DateIssued']);
```

### Ordering Results

```php
// Order by date issued (newest first)
$jobs = $connector->jobs(0)->list()
    ->orderByDesc('DateIssued')
    ->items();

// Order by due date
$jobs = $connector->jobs(0)->list()
    ->orderBy('DueDate')
    ->items();

// Order by name
$jobs = $connector->jobs(0)->list()
    ->orderBy('Name')
    ->items();

// Order by total value (highest first)
$jobs = $connector->jobs(0)->list()
    ->orderByDesc('Total')
    ->items();
```

## Getting a Single Job

### Get by ID

Returns complete information for a specific job:

```php
$job = $connector->jobs(0)->get(123);

return [
    'id' => $job->id,
    'type' => $job->type,
    'name' => $job->name,
    'status' => $job->status,
    'stage' => $job->stage,
    'customer' => $job->customer?->companyName,
    'site' => $job->site?->name,
    'dateIssued' => $job->dateIssued?->format('Y-m-d'),
    'dueDate' => $job->dueDate?->format('Y-m-d'),
    'description' => $job->description,
    'totals' => $job->totals,
];
```

### Get with Specific Columns

Optionally specify which columns to return:

```php
$job = $connector->jobs(0)->get(123, ['ID', 'Name', 'Status', 'Total']);
```

## Creating a Job

Create a new job:

```php
$jobId = $connector->jobs(0)->create([
    'Name' => 'Kitchen Renovation',
    'Type' => 'Project',
    'Customer' => ['ID' => 456],
    'Site' => ['ID' => 789],
    'DateIssued' => '2024-01-15',
    'DueDate' => '2024-03-15',
    'Description' => 'Complete kitchen renovation including cabinets and appliances',
]);

echo "Created job with ID: {$jobId}\n";
```

### Create Service Job

```php
$jobId = $connector->jobs(0)->create([
    'Name' => 'Emergency Plumbing Repair',
    'Type' => 'Service',
    'Customer' => ['ID' => 456],
    'Site' => ['ID' => 789],
    'DateIssued' => date('Y-m-d'),
    'Description' => 'Leaking pipe in bathroom - urgent',
    'ResponseTime' => '4 hours',
]);
```

### Create Job from Quote

```php
$jobId = $connector->jobs(0)->create([
    'Name' => 'Office Fit-out',
    'Type' => 'Project',
    'Customer' => ['ID' => 456],
    'Site' => ['ID' => 789],
    'ConvertedFrom' => ['ID' => 321], // Quote ID
    'DateIssued' => date('Y-m-d'),
]);
```

### Create Job with Full Details

```php
$jobId = $connector->jobs(0)->create([
    'Name' => 'Commercial HVAC Installation',
    'Type' => 'Project',
    'Customer' => ['ID' => 456],
    'CustomerContact' => ['ID' => 111],
    'Site' => ['ID' => 789],
    'SiteContact' => ['ID' => 222],
    'DateIssued' => '2024-01-15',
    'DueDate' => '2024-06-30',
    'DueTime' => '17:00',
    'Description' => 'Install commercial HVAC system in new building',
    'Notes' => 'Access via loading dock - notify security 24h before',
    'OrderNo' => 'PO-2024-001',
    'RequestNo' => 'REQ-12345',
    'Salesperson' => ['ID' => 333],
    'ProjectManager' => ['ID' => 444],
    'Tags' => [
        ['ID' => 1],
        ['ID' => 2],
    ],
]);
```

## Updating a Job

Update an existing job:

```php
$response = $connector->jobs(0)->update(123, [
    'Name' => 'Kitchen Renovation - Phase 2',
    'DueDate' => '2024-04-15',
]);

if ($response->successful()) {
    echo "Job updated successfully\n";
}
```

### Update Job Status

```php
$response = $connector->jobs(0)->update(123, [
    'Status' => 'Complete',
]);
```

### Update Job Stage

```php
$response = $connector->jobs(0)->update(123, [
    'Stage' => 'In Progress',
]);
```

### Update Job Assignment

```php
$response = $connector->jobs(0)->update(123, [
    'ProjectManager' => ['ID' => 555],
    'Technician' => ['ID' => 666],
]);
```

## Deleting a Job

Delete a job:

```php
$response = $connector->jobs(0)->delete(123);

if ($response->successful()) {
    echo "Job deleted successfully\n";
}
```

**Note:** Deleting jobs may be restricted based on job status and associated invoices/schedules.

## Response Structures

### JobListItem (List Response)

Lightweight object returned by `list()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Job ID |
| `type` | `string` | Job type (Project, Service, etc.) |
| `name` | `?string` | Job name |
| `site` | `?string` | Site name |
| `siteId` | `?string` | Site ID |
| `status` | `?string` | Job status |
| `stage` | `?string` | Job stage |
| `customer` | `?string` | Customer name |
| `customerId` | `?string` | Customer ID |
| `dateIssued` | `?string` | Date issued (YYYY-MM-DD) |
| `total` | `?float` | Job total value |

### Job (Detailed Response)

Complete job object returned by `get()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Job ID |
| `type` | `string` | Job type (Project, Service, etc.) |
| `name` | `?string` | Job name |
| `customer` | `?JobCustomer` | Customer details |
| `customerContract` | `?JobContract` | Customer contract (if applicable) |
| `customerContact` | `?JobContact` | Primary customer contact |
| `additionalContacts` | `?array<JobContact>` | Additional contacts |
| `site` | `?JobSite` | Site details |
| `siteContact` | `?JobContact` | Site contact |
| `orderNo` | `?string` | Customer order/PO number |
| `requestNo` | `?string` | Request number |
| `description` | `?string` | Job description |
| `notes` | `?string` | Internal notes |
| `dateIssued` | `?DateTimeImmutable` | Date issued |
| `dueDate` | `?DateTimeImmutable` | Due date |
| `dueTime` | `?string` | Due time |
| `tags` | `?array<JobTag>` | Job tags |
| `salesperson` | `?JobStaff` | Assigned salesperson |
| `projectManager` | `?JobStaff` | Project manager |
| `technicians` | `?array<JobStaff>` | Assigned technicians |
| `technician` | `?JobStaff` | Primary technician |
| `stage` | `?string` | Job stage |
| `status` | `JobStatus\|string\|null` | Job status |
| `responseTime` | `?string` | Response time requirement |
| `isVariation` | `?bool` | Whether this is a variation |
| `linkedVariations` | `?array<JobReference>` | Linked variations |
| `convertedFromQuote` | `?JobReference` | Source quote (if converted) |
| `convertedFrom` | `?JobReference` | Source entity |
| `sections` | `?array<JobSection>` | Job sections |
| `dateModified` | `?DateTimeImmutable` | Last modification date |
| `autoAdjustStatus` | `?bool` | Auto-adjust status enabled |
| `isRetentionEnabled` | `?bool` | Retention enabled |
| `total` | `JobTotal\|float\|null` | Total value |
| `totals` | `?JobTotals` | Detailed totals breakdown |
| `customFields` | `?array<JobCustomField>` | Custom field values |
| `stc` | `?JobStc` | STC information (solar) |
| `completedDate` | `?DateTimeImmutable` | Completion date |

### Nested Objects

**JobCustomer:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Customer ID |
| `companyName` | `?string` | Company name |
| `type` | `?string` | Customer type |

**JobSite:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Site ID |
| `name` | `?string` | Site name |
| `address` | `?string` | Site address |

**JobContact:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Contact ID |
| `name` | `?string` | Contact name |
| `email` | `?string` | Email |
| `phone` | `?string` | Phone |

**JobStaff:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Staff ID |
| `name` | `?string` | Staff name |

**JobTag:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Tag ID |
| `name` | `?string` | Tag name |

**JobSection:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Section ID |
| `name` | `?string` | Section name |
| `costCenters` | `?array<JobCostCenter>` | Cost centers |

**JobStatus:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Status ID |
| `name` | `?string` | Status name |

**JobTotals:**
| Property | Type | Description |
|----------|------|-------------|
| `quoted` | `?JobTotal` | Quoted amounts |
| `actual` | `?JobTotal` | Actual amounts |
| `invoiced` | `?JobTotal` | Invoiced amounts |

**JobTotal:**
| Property | Type | Description |
|----------|------|-------------|
| `exTax` | `?float` | Amount excluding tax |
| `tax` | `?float` | Tax amount |
| `incTax` | `?float` | Amount including tax |

## Examples

### List Active Jobs

```php
$jobs = $connector->jobs(0)->list()
    ->where('Status', '=', 'In Progress')
    ->orderBy('DueDate')
    ->all();

foreach ($jobs as $job) {
    echo "{$job->name}: Due {$job->dueDate} ({$job->customer})\n";
}
```

### Find Overdue Jobs

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$today = date('Y-m-d');

$overdueJobs = $connector->jobs(0)->list()
    ->search([
        Search::make()->column('DueDate')->lessThan($today),
        Search::make()->column('Status')->notEqual('Complete'),
    ])
    ->matchAll()
    ->orderBy('DueDate')
    ->all();

foreach ($overdueJobs as $job) {
    $daysOverdue = (strtotime($today) - strtotime($job->dateIssued)) / 86400;
    echo "{$job->name}: {$daysOverdue} days overdue\n";
}
```

### Get Jobs for Customer

```php
$customerId = 456;

$jobs = $connector->jobs(0)->list()
    ->where('CustomerID', '=', $customerId)
    ->orderByDesc('DateIssued')
    ->all();

$summary = [
    'totalJobs' => count($jobs),
    'totalValue' => 0,
    'jobs' => [],
];

foreach ($jobs as $job) {
    $summary['totalValue'] += $job->total ?? 0;
    $summary['jobs'][] = [
        'id' => $job->id,
        'name' => $job->name,
        'status' => $job->status,
        'total' => $job->total,
    ];
}

return $summary;
```

### Get Job with Full Details

```php
$jobId = 123;
$job = $connector->jobs(0)->get($jobId);

$details = [
    'job' => [
        'id' => $job->id,
        'type' => $job->type,
        'name' => $job->name,
        'status' => is_object($job->status) ? $job->status->name : $job->status,
        'stage' => $job->stage,
        'description' => $job->description,
        'notes' => $job->notes,
        'orderNo' => $job->orderNo,
        'requestNo' => $job->requestNo,
    ],
    'dates' => [
        'issued' => $job->dateIssued?->format('F j, Y'),
        'due' => $job->dueDate?->format('F j, Y'),
        'dueTime' => $job->dueTime,
        'completed' => $job->completedDate?->format('F j, Y'),
        'modified' => $job->dateModified?->format('Y-m-d H:i'),
    ],
];

if ($job->customer !== null) {
    $details['customer'] = [
        'id' => $job->customer->id,
        'name' => $job->customer->companyName,
    ];
}

if ($job->site !== null) {
    $details['site'] = [
        'id' => $job->site->id,
        'name' => $job->site->name,
        'address' => $job->site->address,
    ];
}

if ($job->projectManager !== null) {
    $details['projectManager'] = [
        'id' => $job->projectManager->id,
        'name' => $job->projectManager->name,
    ];
}

if ($job->totals !== null) {
    $details['financials'] = [
        'quoted' => $job->totals->quoted?->incTax ?? 0,
        'actual' => $job->totals->actual?->incTax ?? 0,
        'invoiced' => $job->totals->invoiced?->incTax ?? 0,
    ];
}

if ($job->tags !== null && count($job->tags) > 0) {
    $details['tags'] = array_map(fn($tag) => $tag->name, $job->tags);
}

return $details;
```

### Job Pipeline Summary

```php
$jobs = $connector->jobs(0)->list()->all();

$pipeline = [
    'pending' => ['count' => 0, 'value' => 0],
    'in_progress' => ['count' => 0, 'value' => 0],
    'complete' => ['count' => 0, 'value' => 0],
];

foreach ($jobs as $job) {
    $status = strtolower($job->status ?? 'unknown');

    if (str_contains($status, 'pending')) {
        $pipeline['pending']['count']++;
        $pipeline['pending']['value'] += $job->total ?? 0;
    } elseif (str_contains($status, 'progress')) {
        $pipeline['in_progress']['count']++;
        $pipeline['in_progress']['value'] += $job->total ?? 0;
    } elseif (str_contains($status, 'complete')) {
        $pipeline['complete']['count']++;
        $pipeline['complete']['value'] += $job->total ?? 0;
    }
}

echo "Job Pipeline:\n";
foreach ($pipeline as $stage => $data) {
    echo "  {$stage}: {$data['count']} jobs, \$" . number_format($data['value'], 2) . "\n";
}
```

### Service Jobs Due Today

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$today = date('Y-m-d');

$serviceJobs = $connector->jobs(0)->list()
    ->search([
        Search::make()->column('Type')->equals('Service'),
        Search::make()->column('DueDate')->equals($today),
        Search::make()->column('Status')->notEqual('Complete'),
    ])
    ->matchAll()
    ->orderBy('DueTime')
    ->all();

foreach ($serviceJobs as $job) {
    echo "{$job->name} - Due: {$job->dueDate}\n";
    echo "  Customer: {$job->customer}\n";
    echo "  Site: {$job->site}\n";
}
```

### Calculate Job Profitability

```php
$jobId = 123;
$job = $connector->jobs(0)->get($jobId);

if ($job->totals !== null) {
    $quoted = $job->totals->quoted?->exTax ?? 0;
    $actual = $job->totals->actual?->exTax ?? 0;
    $invoiced = $job->totals->invoiced?->exTax ?? 0;

    $variance = $quoted - $actual;
    $variancePercent = $quoted > 0 ? ($variance / $quoted) * 100 : 0;

    $outstanding = $actual - $invoiced;

    echo "Job: {$job->name}\n";
    echo "  Quoted: \$" . number_format($quoted, 2) . "\n";
    echo "  Actual: \$" . number_format($actual, 2) . "\n";
    echo "  Variance: \$" . number_format($variance, 2) . " (" . number_format($variancePercent, 1) . "%)\n";
    echo "  Invoiced: \$" . number_format($invoiced, 2) . "\n";
    echo "  Outstanding: \$" . number_format($outstanding, 2) . "\n";
}
```

### Jobs with Custom Fields

```php
$jobId = 123;
$job = $connector->jobs(0)->get($jobId);

if ($job->customFields !== null) {
    echo "Custom Fields for {$job->name}:\n";
    foreach ($job->customFields as $field) {
        echo "  {$field->name}: {$field->value}\n";
    }
}
```

### Export Jobs to CSV

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$startDate = '2024-01-01';
$endDate = '2024-12-31';

$jobs = $connector->jobs(0)->list()
    ->search(Search::make()->column('DateIssued')->between($startDate, $endDate))
    ->orderBy('DateIssued')
    ->all();

$csv = "ID,Name,Type,Customer,Site,Date Issued,Due Date,Total,Status,Stage\n";
foreach ($jobs as $job) {
    $csv .= implode(',', [
        $job->id,
        '"' . str_replace('"', '""', $job->name ?? '') . '"',
        '"' . ($job->type ?? '') . '"',
        '"' . str_replace('"', '""', $job->customer ?? '') . '"',
        '"' . str_replace('"', '""', $job->site ?? '') . '"',
        $job->dateIssued ?? '',
        '', // Due date not in list response
        $job->total ?? 0,
        '"' . ($job->status ?? '') . '"',
        '"' . ($job->stage ?? '') . '"',
    ]) . "\n";
}

file_put_contents('jobs_export.csv', $csv);
echo "Exported " . count($jobs) . " jobs\n";
```

### Find Jobs by Tag

```php
// Note: Tag filtering may need to be done client-side
// as tag search varies by Simpro configuration

$jobs = $connector->jobs(0)->list()
    ->where('Status', '!=', 'Complete')
    ->all();

$targetTag = 'Urgent';
$taggedJobs = [];

foreach ($jobs as $job) {
    $full = $connector->jobs(0)->get($job->id);
    if ($full->tags !== null) {
        foreach ($full->tags as $tag) {
            if ($tag->name === $targetTag) {
                $taggedJobs[] = $full;
                break;
            }
        }
    }
}

echo "Jobs tagged '{$targetTag}':\n";
foreach ($taggedJobs as $job) {
    echo "  {$job->id}: {$job->name}\n";
}
```

## Performance Considerations

### Pagination

Job lists are paginated automatically. The default page size is 30:

```php
$builder = $connector->jobs(0)->list();

// Change page size if needed
$builder->getPaginator()->setPerPageLimit(100);

// Iterate automatically handles pagination
foreach ($builder->items() as $job) {
    // Processes all jobs across all pages
}
```

### Date Range Filtering

Always filter by date range when possible to reduce the result set:

```php
// Good - filtered query
$jobs = $connector->jobs(0)->list()
    ->search(Search::make()->column('DateIssued')->between('2024-01-01', '2024-01-31'))
    ->items();

// Less efficient - no date filter
$jobs = $connector->jobs(0)->list()->items();
```

### Caching Strategy

Consider caching job summaries:

```php
use Illuminate\Support\Facades\Cache;

// Cache active job count for 5 minutes
$activeJobCount = Cache::remember('simpro.jobs.active_count', 300, function () use ($connector) {
    return $connector->jobs(0)->list()
        ->where('Status', '=', 'In Progress')
        ->getTotalResults();
});
```

## Error Handling

### Validation Errors

Handle validation errors when creating or updating jobs:

```php
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;

try {
    $jobId = $connector->jobs(0)->create([
        'Name' => '', // Empty name
        'Customer' => ['ID' => 99999], // Invalid customer
    ]);
} catch (ValidationException $e) {
    $errors = $e->getErrors();

    foreach ($errors as $field => $messages) {
        echo "{$field}: " . implode(', ', $messages) . "\n";
    }
}
```

### Not Found Errors

Handle cases where a job doesn't exist:

```php
use Saloon\Exceptions\Request\ClientException;

try {
    $job = $connector->jobs(0)->get(99999);
} catch (ClientException $e) {
    if ($e->getResponse()->status() === 404) {
        echo "Job not found\n";
    } else {
        throw $e;
    }
}
```

## Future Enhancements

The following features are planned for future SDK releases:

- **Job Sections**: Managing sections within jobs
- **Job Cost Centers**: Cost center management
- **Job Attachments**: File and folder attachments
- **Job Notes**: Notes and comments
- **Job Schedules**: Scheduling work blocks
- **Job Custom Fields**: Custom field management
