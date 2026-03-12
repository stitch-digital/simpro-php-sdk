# Tasks Resource

The Tasks resource provides read-only access to tasks across your Simpro environment at the company level.

## Basic Usage

```php
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;

$connector = new SimproApiKeyConnector(
    baseUrl: 'https://your-instance.simprocloud.com',
    apiKey: 'your-api-key'
);

// Access tasks for a company
$tasks = $connector->tasks(companyId: 0);
```

## Available Methods

### List Tasks

```php
// List all tasks
$tasks = $connector->tasks(0)->list()->all();

foreach ($tasks as $task) {
    echo "{$task->id}: {$task->subject}\n";
    echo "  Assigned to: {$task->assignedTo?->name}\n";
    echo "  Due: {$task->dueDate}\n";
}

// With search
use Simpro\PhpSdk\Simpro\Query\Search;

$tasks = $connector->tasks(0)->list()
    ->search(Search::make()->column('Status')->is('Pending'))
    ->orderByDesc('ID')
    ->all();
```

### List Tasks (Detailed)

The detailed list includes additional fields: CreatedBy, Associated, Description, Status, Priority, Category, Estimated, Actual, CustomFields, DateModified, and more.

```php
// Detailed list with all columns
$tasks = $connector->tasks(0)->listDetailed()->all();

foreach ($tasks as $task) {
    echo "{$task->id}: {$task->subject}\n";
    echo "  Status: {$task->status} | Priority: {$task->priority}\n";
    echo "  Created by: {$task->createdBy?->name}\n";

    if ($task->associated?->job) {
        echo "  Job: #{$task->associated->job->id}\n";
    }

    if ($task->associated?->customer) {
        echo "  Customer: {$task->associated->customer->companyName}\n";
    }

    if ($task->associated?->site) {
        echo "  Site: {$task->associated->site->name}\n";
    }
}
```

## DTOs

### TaskListItem

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Task ID |
| `subject` | `?string` | Task subject |
| `assignedTo` | `?TaskAssignee` | Primary assignee |
| `assignees` | `?array<TaskAssignee>` | All assignees |
| `completedBy` | `?TaskAssignee` | Who completed the task |
| `dueDate` | `?string` | Due date |
| `percentComplete` | `?string` | Completion percentage |

### TaskListDetailedItem

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Task ID |
| `subject` | `?string` | Task subject |
| `createdBy` | `?TaskAssignee` | Who created the task |
| `assignedTo` | `?TaskAssignee` | Primary assignee |
| `assignees` | `?array<TaskAssignee>` | All assignees |
| `assignedToCustomer` | `?bool` | Whether assigned to a customer |
| `completedBy` | `?TaskAssignee` | Who completed the task |
| `associated` | `?TaskAssociated` | Associated entities (job, quote, customer, contact, site) |
| `isBillable` | `?bool` | Whether the task is billable |
| `showOnWorkOrder` | `?bool` | Whether shown on work order |
| `emailNotifications` | `?bool` | Whether email notifications are enabled |
| `description` | `?string` | Task description (HTML) |
| `startDate` | `?string` | Start date |
| `dueDate` | `?string` | Due date |
| `completedDate` | `?string` | Completion date |
| `notes` | `?string` | Task notes |
| `status` | `?string` | Task status (e.g. "Pending") |
| `priority` | `?string` | Task priority (e.g. "High", "Medium", "Urgent") |
| `category` | `?Reference` | Task category |
| `estimated` | `?TaskDuration` | Estimated time |
| `actual` | `?TaskDuration` | Actual time spent |
| `parentTask` | `?Reference` | Parent task reference |
| `subTasks` | `?array<int>` | Sub-task IDs |
| `customFields` | `?array<CustomField>` | Custom field values |
| `percentComplete` | `?string` | Completion percentage |
| `dateModified` | `?DateTimeImmutable` | Last modification timestamp |

### TaskAssignee

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Assignee ID |
| `name` | `?string` | Assignee name |
| `type` | `?string` | Entity type (e.g. "employee") |
| `typeId` | `?int` | Type-specific ID |

### TaskAssociated

| Field | Type | Description |
|-------|------|-------------|
| `job` | `?TaskAssociatedJob` | Associated job |
| `quote` | `?Reference` | Associated quote |
| `customer` | `?TaskAssociatedCustomer` | Associated customer |
| `contact` | `?Reference` | Associated contact |
| `site` | `?Reference` | Associated site |

### TaskAssociatedJob

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Job ID |
| `costCenter` | `?Reference` | Cost center reference |

### TaskAssociatedCustomer

| Field | Type | Description |
|-------|------|-------------|
| `id` | `int` | Customer ID |
| `companyName` | `?string` | Company name |
| `givenName` | `?string` | Given name |
| `familyName` | `?string` | Family name |

### TaskDuration

| Field | Type | Description |
|-------|------|-------------|
| `hours` | `int` | Hours |
| `minutes` | `int` | Minutes |
| `seconds` | `int` | Seconds |

## Pagination

Tasks support pagination through the QueryBuilder:

```php
// Iterate through all pages
foreach ($connector->tasks(0)->list()->items() as $task) {
    echo "{$task->id}: {$task->subject}\n";
}

// Get first result only
$first = $connector->tasks(0)->list()->first();
```

## Limitations

The Tasks resource is **read-only**. Only `list()` and `listDetailed()` are currently supported.
