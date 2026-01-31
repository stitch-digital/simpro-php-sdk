# Activity Schedules Resource

The Activity Schedules resource allows you to manage scheduled activities for staff members.

## Basic Usage

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://your-instance.simprocloud.com',
    apiKey: 'your-api-key'
);

// Access activity schedules for a company
$activitySchedules = $connector->activitySchedules(companyId: 0);
```

## Available Methods

### List Activity Schedules

```php
// List with summary fields (ActivityScheduleListItem)
$schedules = $activitySchedules->list()->all();

// List with full details (ActivitySchedule)
$schedules = $activitySchedules->listDetailed()->all();

// With filtering
$schedules = $activitySchedules->list([
    'Staff.ID' => 123,
])->all();

// With search
use Simpro\PhpSdk\Simpro\Query\Search;

$schedules = $activitySchedules->list()
    ->search(Search::make()->column('Date')->greaterThanOrEqual('2025-01-01'))
    ->all();
```

### Get a Single Activity Schedule

```php
$schedule = $activitySchedules->get(scheduleId: 9909);

// With specific columns
$schedule = $activitySchedules->get(9909, columns: ['ID', 'TotalHours', 'Staff']);
```

### Create an Activity Schedule

```php
$scheduleId = $activitySchedules->create([
    'Staff' => ['ID' => 2335],
    'Activity' => ['ID' => 801],
    'Date' => '2025-12-20',
    'Blocks' => [
        [
            'StartTime' => '09:00',
            'EndTime' => '10:00',
            'ScheduleRate' => ['ID' => 22],
        ],
    ],
]);
```

### Update an Activity Schedule

```php
$response = $activitySchedules->update(9909, [
    'Notes' => 'Updated notes for this schedule',
]);
```

### Delete an Activity Schedule

```php
$response = $activitySchedules->delete(9909);
```

## DTOs

### ActivityScheduleListItem

Returned by `list()`. Contains summary fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Schedule ID |
| `totalHours` | `?float` | Total scheduled hours |
| `staff` | `?StaffReference` | Staff member (ID, Name, Type, TypeId) |
| `date` | `?string` | Schedule date (YYYY-MM-DD) |
| `activity` | `?Reference` | Activity (ID, Name) |

### ActivitySchedule

Returned by `listDetailed()` and `get()`. Contains all fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Schedule ID |
| `totalHours` | `?float` | Total scheduled hours |
| `notes` | `?string` | Schedule notes (may contain HTML) |
| `isLocked` | `?bool` | Whether the schedule is locked |
| `recurringScheduleId` | `?string` | ID of parent recurring schedule |
| `staff` | `?StaffReference` | Staff member (ID, Name, Type, TypeId) |
| `date` | `?string` | Schedule date (YYYY-MM-DD) |
| `blocks` | `?array<ActivityScheduleBlock>` | Time blocks for the schedule |
| `dateModified` | `?DateTimeImmutable` | Last modification date |
| `activity` | `?Reference` | Activity (ID, Name) |

### ActivityScheduleBlock

Represents a time block within a schedule:

| Field | Type | Description |
|-------|------|-------------|
| `hrs` | `?float` | Duration in hours |
| `startTime` | `?string` | Start time (HH:MM) |
| `iso8601StartTime` | `?DateTimeImmutable` | ISO 8601 start datetime |
| `endTime` | `?string` | End time (HH:MM) |
| `iso8601EndTime` | `?DateTimeImmutable` | ISO 8601 end datetime |
| `scheduleRate` | `?Reference` | Schedule rate (ID, Name) |

## Pagination

Activity schedules support pagination through the QueryBuilder:

```php
// Get first page
$firstPage = $activitySchedules->list()->first();

// Iterate through all pages
foreach ($activitySchedules->list() as $schedule) {
    echo $schedule->id . ': ' . $schedule->date . "\n";
}

// Get specific page
$page = $activitySchedules->list()->getPage(2);
```
