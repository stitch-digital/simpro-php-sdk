# Schedules Resource

The Schedules resource provides read-only access to job, quote, lead, and activity schedules in your Simpro environment. Schedules represent time blocks assigned to staff members.

## Basic Usage

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://your-instance.simprocloud.com',
    apiKey: 'your-api-key'
);

// Access schedules for a company
$schedules = $connector->schedules(companyId: 0);
```

## Available Methods

### List Schedules

```php
// List with summary fields (ScheduleListItem)
$schedules = $connector->schedules(0)->list()->all();

// List with full details (Schedule)
$schedules = $connector->schedules(0)->listDetailed()->all();

// With filtering
$schedules = $connector->schedules(0)->list([
    'Staff.ID' => 123,
])->all();

// With search
use Simpro\PhpSdk\Simpro\Query\Search;

$schedules = $connector->schedules(0)->list()
    ->search(Search::make()->column('Date')->equals('2024-01-15'))
    ->orderBy('Date')
    ->all();

// Filter by type (job, quote, lead, activity)
$schedules = $connector->schedules(0)->list()
    ->where('Type', '=', 'job')
    ->all();
```

### Get a Single Schedule

```php
$schedule = $connector->schedules(0)->get(scheduleId: 123);

// With specific columns
$schedule = $connector->schedules(0)->get(123, columns: ['ID', 'Type', 'Staff', 'Date']);
```

## DTOs

### ScheduleListItem

Returned by `list()`. Contains summary fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Schedule ID |
| `type` | `?string` | Type: "job", "quote", "lead", or "activity" |
| `reference` | `?string` | Reference identifier (activity ID, job-costcenter, or lead ID) |
| `totalHours` | `?float` | Total scheduled hours |
| `staff` | `?StaffReference` | Staff member (ID, Name, Type, TypeId) |
| `date` | `?string` | Schedule date (YYYY-MM-DD) |
| `blocks` | `?array<ScheduleBlock>` | Time blocks for the schedule |

### Schedule

Returned by `listDetailed()` and `get()`. Contains all fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Schedule ID |
| `type` | `?string` | Type: "job", "quote", "lead", or "activity" |
| `reference` | `?string` | Reference identifier |
| `totalHours` | `?float` | Total scheduled hours |
| `notes` | `?string` | Schedule notes |
| `staff` | `?StaffReference` | Staff member (ID, Name, Type, TypeId) |
| `date` | `?string` | Schedule date (YYYY-MM-DD) |
| `blocks` | `?array<ScheduleBlock>` | Time blocks for the schedule |
| `href` | `?string` | Link to this schedule |
| `dateModified` | `?DateTimeImmutable` | Last modification date |

### ScheduleBlock

Represents a time block within a schedule:

| Field | Type | Description |
|-------|------|-------------|
| `hrs` | `?float` | Duration in hours |
| `startTime` | `?string` | Start time (HH:MM) |
| `iso8601StartTime` | `?DateTimeImmutable` | ISO 8601 start datetime |
| `endTime` | `?string` | End time (HH:MM) |
| `iso8601EndTime` | `?DateTimeImmutable` | ISO 8601 end datetime |
| `scheduleRate` | `?Reference` | Schedule rate (ID, Name) |

### StaffReference

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Staff ID |
| `name` | `?string` | Staff name |
| `type` | `?string` | Type: "employee", "contractor", or "plant" |
| `typeId` | `?int` | Employee/Contractor/Plant ID |

## Reference Field Format

The `reference` field format varies by schedule type:

- **activity**: Activity type ID (e.g., "801")
- **job/quote**: Job/Quote ID and cost center ID separated by hyphen (e.g., "100-233")
- **lead**: Lead ID (e.g., "456")

## Examples

### Get Today's Schedules

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$today = date('Y-m-d');

$schedules = $connector->schedules(0)->list()
    ->search(Search::make()->column('Date')->equals($today))
    ->orderBy('Date')
    ->all();

foreach ($schedules as $schedule) {
    $staffName = $schedule->staff?->name ?? 'Unassigned';
    echo "{$schedule->type}: {$schedule->reference} - {$staffName}\n";

    foreach ($schedule->blocks as $block) {
        echo "  {$block->startTime} - {$block->endTime} ({$block->hrs}h)\n";
    }
}
```

### Get This Week's Job Schedules

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

$schedules = $connector->schedules(0)->list()
    ->search(Search::make()->column('Date')->between($startOfWeek, $endOfWeek))
    ->where('Type', '=', 'job')
    ->orderBy('Date')
    ->all();
```

### Get Schedules for a Staff Member

```php
$staffId = 456;

$schedules = $connector->schedules(0)->list()
    ->where('Staff.ID', '=', $staffId)
    ->orderByDesc('Date')
    ->all();

foreach ($schedules as $schedule) {
    echo "{$schedule->date}: {$schedule->reference} ({$schedule->totalHours}h)\n";
}
```

### Check Staff Availability

```php
$staffId = 123;
$checkDate = '2024-01-15';
$checkStart = '09:00';
$checkEnd = '12:00';

$schedules = $connector->schedules(0)->list()
    ->where('Staff.ID', '=', $staffId)
    ->where('Date', '=', $checkDate)
    ->all();

$isAvailable = true;
foreach ($schedules as $schedule) {
    foreach ($schedule->blocks as $block) {
        if ($block->startTime < $checkEnd && $block->endTime > $checkStart) {
            $isAvailable = false;
            echo "Conflict: {$schedule->reference} ({$block->startTime}-{$block->endTime})\n";
        }
    }
}

if ($isAvailable) {
    echo "Staff member is available.\n";
}
```

### Build Calendar View Data

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$startDate = '2024-01-01';
$endDate = '2024-01-31';

$schedules = $connector->schedules(0)->list()
    ->search(Search::make()->column('Date')->between($startDate, $endDate))
    ->orderBy('Date')
    ->all();

$calendarEvents = [];
foreach ($schedules as $schedule) {
    foreach ($schedule->blocks as $block) {
        $calendarEvents[] = [
            'id' => $schedule->id,
            'title' => $schedule->reference,
            'start' => $schedule->date . 'T' . ($block->startTime ?? '00:00'),
            'end' => $schedule->date . 'T' . ($block->endTime ?? '23:59'),
            'resourceId' => $schedule->staff?->id,
            'type' => $schedule->type,
        ];
    }
}

return $calendarEvents;
```

## Pagination

Schedules support pagination through the QueryBuilder:

```php
// Get first page
$firstPage = $connector->schedules(0)->list()->first();

// Iterate through all pages
foreach ($connector->schedules(0)->list() as $schedule) {
    echo $schedule->id . ': ' . $schedule->date . "\n";
}

// Get specific page
$page = $connector->schedules(0)->list()->getPage(2);
```

## Limitations

The Schedules resource is **read-only**. To create, update, or delete schedules:

- Use the Job Cost Center Schedules resource for job-related schedules
- Use the Activity Schedules resource for activity schedules
- Use the Simpro web interface for other schedule management
