# Job Work Orders Resource

The Job Work Orders resource provides read-only access to work orders across all jobs in your Simpro environment. This is a top-level endpoint that returns work orders without needing to navigate through individual job/section/costCenter hierarchies.

Two views are available:
- **Basic list** (`list()`) - Returns minimal fields: ID, Staff, WorkOrderDate, Project
- **Detailed list** (`listDetailed()`) - Returns all fields including blocks, materials, custom fields, and scheduled times

## Basic Usage

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://your-instance.simprocloud.com',
    apiKey: 'your-api-key'
);

// Access job work orders for a company
$workOrders = $connector->jobWorkOrders(companyId: 0);
```

## Available Methods

### List Work Orders (Basic)

Returns `JobWorkOrderListItem` objects with summary fields:

```php
$workOrders = $connector->jobWorkOrders(0)->list()->all();

foreach ($workOrders as $wo) {
    echo "{$wo->id}: {$wo->staff?->name} - {$wo->workOrderDate}\n";
    echo "  Job: {$wo->project?->name}\n";
}

// With filtering
$workOrders = $connector->jobWorkOrders(0)->list([
    'Staff.ID' => 10,
])->all();

// With search
use Simpro\PhpSdk\Simpro\Query\Search;

$workOrders = $connector->jobWorkOrders(0)->list()
    ->search(Search::make()->column('WorkOrderDate')->greaterThanOrEqual('2026-01-01'))
    ->orderBy('WorkOrderDate')
    ->all();
```

### List Work Orders (Detailed)

Returns `JobWorkOrderDetailed` objects with all fields including blocks, materials, and custom fields:

```php
$workOrders = $connector->jobWorkOrders(0)->listDetailed()->all();

foreach ($workOrders as $wo) {
    echo "{$wo->id}: {$wo->staff?->name}\n";
    echo "  Description: {$wo->descriptionNotes}\n";
    echo "  Approved: " . ($wo->approved ? 'Yes' : 'No') . "\n";
    echo "  Scheduled: {$wo->scheduledHrs}h\n";

    foreach ($wo->blocks as $block) {
        echo "  Block: {$block->startTime} - {$block->endTime} ({$block->hrs}h)\n";
    }

    foreach ($wo->customFields as $cf) {
        echo "  {$cf->name}: {$cf->value}\n";
    }
}
```

## DTOs

### JobWorkOrderListItem

Returned by `list()`. Contains summary fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Work order ID |
| `staff` | `?StaffReference` | Assigned staff member (ID, Name, Type, TypeId) |
| `workOrderDate` | `?string` | Work order date |
| `project` | `?JobWorkOrderProject` | Project/job reference |

### JobWorkOrderDetailed

Returned by `listDetailed()`. Contains all fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Work order ID |
| `staff` | `?StaffReference` | Assigned staff member |
| `workOrderDate` | `?string` | Work order date |
| `project` | `?JobWorkOrderProject` | Project/job reference |
| `descriptionNotes` | `?string` | Description notes |
| `materialNotes` | `?string` | Material notes |
| `approved` | `?bool` | Whether the work order is approved |
| `materials` | `array` | Materials list |
| `blocks` | `array<JobWorkOrderBlock>` | Scheduled time blocks |
| `scheduledHrs` | `?float` | Total scheduled hours |
| `scheduledStartTime` | `?string` | Scheduled start time |
| `iso8601ScheduledStartTime` | `?DateTimeImmutable` | ISO 8601 scheduled start |
| `scheduledEndTime` | `?string` | Scheduled end time |
| `iso8601ScheduledEndTime` | `?DateTimeImmutable` | ISO 8601 scheduled end |
| `dateModified` | `?DateTimeImmutable` | Last modification date |
| `customFields` | `array<CustomField>` | Custom field values |

### JobWorkOrderProject

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Job/project ID |
| `name` | `?string` | Job/project name |
| `sectionId` | `?int` | Section ID |
| `costCenterId` | `?int` | Cost center ID |
| `costCenterName` | `?string` | Cost center name |

### JobWorkOrderBlock

| Field | Type | Description |
|-------|------|-------------|
| `hrs` | `?float` | Duration in hours |
| `startTime` | `?string` | Start time |
| `iso8601StartTime` | `?DateTimeImmutable` | ISO 8601 start datetime |
| `endTime` | `?string` | End time |
| `iso8601EndTime` | `?DateTimeImmutable` | ISO 8601 end datetime |
| `scheduleRate` | `?Reference` | Schedule rate (ID, Name) |

## Examples

### Get Today's Work Orders

```php
use Simpro\PhpSdk\Simpro\Query\Search;

$today = date('Y-m-d');

$workOrders = $connector->jobWorkOrders(0)->list()
    ->search(Search::make()->column('WorkOrderDate')->equals($today))
    ->all();

foreach ($workOrders as $wo) {
    echo "{$wo->staff?->name}: Job {$wo->project?->name}\n";
}
```

### Get Work Orders for a Staff Member

```php
$workOrders = $connector->jobWorkOrders(0)->listDetailed()
    ->where('Staff.ID', '=', 123)
    ->orderByDesc('WorkOrderDate')
    ->all();

foreach ($workOrders as $wo) {
    echo "{$wo->workOrderDate}: {$wo->descriptionNotes}\n";
    echo "  Scheduled: {$wo->scheduledHrs}h ({$wo->scheduledStartTime} - {$wo->scheduledEndTime})\n";
}
```

### Get Unapproved Work Orders

```php
$unapproved = $connector->jobWorkOrders(0)->listDetailed()
    ->where('Approved', '=', false)
    ->all();

foreach ($unapproved as $wo) {
    echo "WO #{$wo->id}: {$wo->descriptionNotes} ({$wo->staff?->name})\n";
}
```

### Calculate Total Scheduled Hours by Staff

```php
$workOrders = $connector->jobWorkOrders(0)->listDetailed()->all();

$hoursByStaff = [];
foreach ($workOrders as $wo) {
    $staffName = $wo->staff?->name ?? 'Unassigned';
    $hoursByStaff[$staffName] = ($hoursByStaff[$staffName] ?? 0) + ($wo->scheduledHrs ?? 0);
}

arsort($hoursByStaff);
foreach ($hoursByStaff as $name => $hours) {
    echo "{$name}: {$hours}h\n";
}
```

## Pagination

Job work orders support pagination through the QueryBuilder:

```php
// Iterate through all pages
foreach ($connector->jobWorkOrders(0)->list()->items() as $wo) {
    echo "{$wo->id}: {$wo->workOrderDate}\n";
}

// Get first result
$first = $connector->jobWorkOrders(0)->list()->first();
```

## Limitations

The Job Work Orders resource is **read-only** at the top level. To manage work orders within a specific job, use the Jobs resource's nested work order endpoints.
