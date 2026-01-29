# Schedules Resource

The Schedules resource provides read-only access to job and activity schedules in your Simpro environment. Schedules represent time blocks assigned to staff members for specific jobs or activities.

## Listing Schedules

### Basic List

Returns all schedules with pagination support. The list returns `ScheduleListItem` objects:

```php
$schedules = $connector->schedules(companyId: 0)->list();

foreach ($schedules->items() as $schedule) {
    $staffName = $schedule->staff?->name ?? 'Unassigned';
    echo "{$schedule->id}: {$schedule->reference} ({$schedule->date}) - {$staffName}\n";
}
```

**Note:** For full schedule details including notes and job information, use `get()` to retrieve individual schedules by ID.

### Filtering Schedules

Both `list()` accepts filter parameters. Use the fluent search API or array-based filters:

#### Fluent Search API (Recommended)

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Find schedules for a specific date
$schedules = $connector->schedules(companyId: 0)->list()
    ->search(Search::make()->column('Date')->equals('2024-01-15'))
    ->items();

// Find schedules for a specific staff member
$schedules = $connector->schedules(companyId: 0)->list()
    ->where('Staff.ID', '=', 123)
    ->items();

// Find schedules within a date range
$schedules = $connector->schedules(companyId: 0)->list()
    ->search(Search::make()->column('Date')->between('2024-01-01', '2024-01-31'))
    ->orderBy('Date')
    ->items();

// Find schedules by type
$schedules = $connector->schedules(companyId: 0)->list()
    ->where('Type', '=', 'Job')
    ->items();
```

#### Array-Based Syntax

```php
// Filter by date
$schedules = $connector->schedules(companyId: 0)->list(['Date' => '2024-01-15']);

// Filter by staff
$schedules = $connector->schedules(companyId: 0)->list(['Staff.ID' => 123]);

// Multiple filters
$schedules = $connector->schedules(companyId: 0)->list([
    'Date' => '2024-01-15',
    'Type' => 'Job',
]);
```

### Ordering Results

```php
// Order by date ascending (default)
$schedules = $connector->schedules(companyId: 0)->list()
    ->orderBy('Date')
    ->items();

// Order by date descending
$schedules = $connector->schedules(companyId: 0)->list()
    ->orderByDesc('Date')
    ->items();

// Order by start time
$schedules = $connector->schedules(companyId: 0)->list()
    ->orderBy('StartTime')
    ->items();
```

## Getting a Single Schedule

### Get by ID

Returns complete information for a specific schedule:

```php
$schedule = $connector->schedules(companyId: 0)->get(scheduleId: 123);

return [
    'id' => $schedule->id,
    'type' => $schedule->type,
    'subject' => $schedule->subject,
    'date' => $schedule->date?->format('Y-m-d'),
    'startTime' => $schedule->startTime,
    'endTime' => $schedule->endTime,
    'staff' => $schedule->staff?->name,
    'job' => $schedule->job?->id,
    'notes' => $schedule->notes,
];
```

### Get with Specific Columns

Optionally specify which columns to return:

```php
$schedule = $connector->schedules(companyId: 0)->get(scheduleId: 123, columns: ['ID', 'Subject', 'Date', 'StartTime', 'EndTime']);
```

## Response Structures

### ScheduleListItem (List Response)

Object returned by `list()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Schedule ID |
| `type` | `string` | Schedule type (Job, Activity, etc.) |
| `reference` | `?string` | Reference/title |
| `totalHours` | `float` | Total scheduled hours |
| `staff` | `?ScheduleListStaff` | Staff object with id and name |
| `date` | `?string` | Date string (YYYY-MM-DD format) |
| `blocks` | `array<ScheduleBlock>` | Time blocks for this schedule |

**Note:** For full schedule details (notes, job information), use `get()` to retrieve the complete schedule.

### Schedule (Detailed Response)

Complete schedule object returned by `get()`:

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Schedule ID |
| `type` | `?string` | Schedule type (Job, Activity, etc.) |
| `subject` | `?string` | Schedule subject/title |
| `date` | `?DateTimeImmutable` | Schedule date |
| `startTime` | `?string` | Start time (HH:MM format) |
| `endTime` | `?string` | End time (HH:MM format) |
| `staff` | `?ScheduleStaff` | Assigned staff member |
| `job` | `?ScheduleJob` | Associated job (if type is Job) |
| `notes` | `?string` | Schedule notes |
| `dateModified` | `?DateTimeImmutable` | Last modification date |

### Nested Objects

**ScheduleStaff:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Staff member ID |
| `name` | `string` | Staff member name |

**ScheduleJob:**
| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Job ID |
| `name` | `?string` | Job name |

## Examples

### Get Today's Schedules

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$today = date('Y-m-d');

$schedules = $connector->schedules(companyId: 0)->list()
    ->search(Search::make()->column('Date')->equals($today))
    ->orderBy('Date')
    ->all();

foreach ($schedules as $schedule) {
    echo "{$schedule->reference} - {$schedule->totalHours} hours\n";
    if ($schedule->staff !== null) {
        echo "  Assigned to: {$schedule->staff->name}\n";
    }
    // Show time blocks
    foreach ($schedule->blocks as $block) {
        echo "  Block: {$block->startTime} - {$block->endTime}\n";
    }
}
```

### Get This Week's Schedules

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

$schedules = $connector->schedules(companyId: 0)->list()
    ->search(Search::make()->column('Date')->between($startOfWeek, $endOfWeek))
    ->orderBy('Date')
    ->collect()
    ->groupBy(fn($s) => $s->date);

foreach ($schedules as $date => $daySchedules) {
    echo "\n{$date}:\n";
    foreach ($daySchedules as $schedule) {
        $staffName = $schedule->staff?->name ?? 'Unassigned';
        echo "  {$schedule->reference} ({$schedule->totalHours}h) - {$staffName}\n";
    }
}
```

### Get Schedules for a Staff Member

```php
$staffId = 456;

$schedules = $connector->schedules(companyId: 0)->list()
    ->where('Staff.ID', '=', $staffId)
    ->orderByDesc('Date')
    ->collect()
    ->take(10)
    ->all();

foreach ($schedules as $schedule) {
    echo "{$schedule->date}: {$schedule->reference} ({$schedule->totalHours}h)\n";
}
```

### Get Job Schedules Only

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$schedules = $connector->schedules(companyId: 0)->list()
    ->search(Search::make()->column('Type')->equals('Job'))
    ->items();

foreach ($schedules as $schedule) {
    echo "Job Schedule: {$schedule->reference}\n";
    if ($schedule->staff !== null) {
        echo "  Staff: {$schedule->staff->name} (ID: {$schedule->staff->id})\n";
    }
}
```

### Build Calendar View Data

```php
use Simpro\PhpSdk\Simpro\Query\Search;

// Get all schedules for a month
$year = 2024;
$month = 1;
$startDate = sprintf('%04d-%02d-01', $year, $month);
$endDate = date('Y-m-t', strtotime($startDate));

$schedules = $connector->schedules(companyId: 0)->list()
    ->search(Search::make()->column('Date')->between($startDate, $endDate))
    ->orderBy('Date')
    ->all();

// Transform for calendar display
$calendarEvents = [];
foreach ($schedules as $schedule) {
    // Each schedule may have multiple time blocks
    foreach ($schedule->blocks as $block) {
        $calendarEvents[] = [
            'id' => $schedule->id,
            'title' => $schedule->reference,
            'start' => $schedule->date . 'T' . ($block->startTime ?? '00:00'),
            'end' => $schedule->date . 'T' . ($block->endTime ?? '23:59'),
            'resourceId' => $schedule->staff?->id, // For resource-based calendar views
        ];
    }
}

return $calendarEvents;
```

### Check Staff Availability

```php
$staffId = 123;
$checkDate = '2024-01-15';
$checkStart = '09:00';
$checkEnd = '12:00';

$schedules = $connector->schedules(companyId: 0)->list()
    ->where('Staff.ID', '=', $staffId)
    ->where('Date', '=', $checkDate)
    ->all();

$isAvailable = true;
foreach ($schedules as $schedule) {
    // Check each time block for overlap
    foreach ($schedule->blocks as $block) {
        if ($block->startTime < $checkEnd && $block->endTime > $checkStart) {
            $isAvailable = false;
            echo "Conflict: {$schedule->reference} ({$block->startTime}-{$block->endTime})\n";
        }
    }
}

if ($isAvailable) {
    echo "Staff member is available for the requested time slot.\n";
}
```

### Get Schedule Details with Job Info

```php
$scheduleId = 789;
$schedule = $connector->schedules(companyId: 0)->get(scheduleId: $scheduleId);

$details = [
    'schedule' => [
        'id' => $schedule->id,
        'subject' => $schedule->subject,
        'date' => $schedule->date?->format('l, F j, Y'),
        'time' => "{$schedule->startTime} - {$schedule->endTime}",
        'notes' => $schedule->notes,
    ],
];

if ($schedule->staff !== null) {
    $details['staff'] = [
        'id' => $schedule->staff->id,
        'name' => $schedule->staff->name,
    ];
}

if ($schedule->job !== null) {
    $details['job'] = [
        'id' => $schedule->job->id,
        'name' => $schedule->job->name,
    ];
}

return $details;
```

### Export Schedules to CSV

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$startDate = '2024-01-01';
$endDate = '2024-01-31';

$schedules = $connector->schedules(companyId: 0)->list()
    ->search(Search::make()->column('Date')->between($startDate, $endDate))
    ->orderBy('Date')
    ->all();

$csv = "ID,Date,Reference,Staff,Type,Total Hours\n";
foreach ($schedules as $schedule) {
    $csv .= implode(',', [
        $schedule->id,
        $schedule->date,
        '"' . str_replace('"', '""', $schedule->reference ?? '') . '"',
        '"' . str_replace('"', '""', $schedule->staff?->name ?? '') . '"',
        $schedule->type,
        $schedule->totalHours,
    ]) . "\n";
}

file_put_contents('schedules_export.csv', $csv);
```

## Performance Considerations

### Pagination

Schedule lists are paginated automatically. The default page size is 30:

```php
$builder = $connector->schedules(companyId: 0)->list();

// Change page size if needed
$builder->getPaginator()->setPerPageLimit(100);

// Iterate automatically handles pagination
foreach ($builder->items() as $schedule) {
    // Processes all schedules across all pages
}
```

### Date Range Filtering

Always filter by date range when possible to reduce the result set:

```php
// Good - filtered query
$schedules = $connector->schedules(companyId: 0)->list()
    ->search(Search::make()->column('Date')->between('2024-01-01', '2024-01-31'))
    ->items();

// Less efficient - no date filter
$schedules = $connector->schedules(companyId: 0)->list()->items();
```

### Caching Strategy

Consider caching schedule data, especially for calendar views:

```php
use Illuminate\Support\Facades\Cache;

$cacheKey = "schedules.{$companyId}.{$date}";

$schedules = Cache::remember($cacheKey, 300, function () use ($connector, $date) {
    return $connector->schedules(companyId: 0)->list()
        ->search(Search::make()->column('Date')->equals($date))
        ->all();
});
```

## Limitations

The Schedules resource is **read-only**. To create, update, or delete schedules, use the Simpro web interface or consider the following alternatives:

- **Creating Schedules**: Schedule jobs through the Jobs resource workflow
- **Updating Schedules**: Use the Simpro scheduler UI
- **Deleting Schedules**: Remove via Simpro scheduler UI

Future SDK versions may include schedule management endpoints as they become available in the API.
