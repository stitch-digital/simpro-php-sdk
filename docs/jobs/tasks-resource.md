# Job Tasks Resource

> [Jobs](../jobs-resource.md) > Tasks

Read-only access to tasks associated with jobs and cost centers.

## Job Tasks

### Navigation

```php
// Access tasks for a specific job
$connector->jobs(companyId: 0)->job(jobId: 123)->tasks()
```

### Listing Tasks

```php
$tasks = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->tasks()
    ->list()
    ->all();

foreach ($tasks as $task) {
    echo "{$task->id}: {$task->subject}\n";
}
```

### Getting a Single Task

```php
$task = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->tasks()
    ->get(taskId: 456);

echo "Task: {$task->subject}\n";
```

## Cost Center Tasks

### Navigation

```php
// Access tasks for a specific cost center
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->tasks()
```

### Listing Cost Center Tasks

```php
$tasks = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->section(sectionId: 1)
    ->costCenter(costCenterId: 5)
    ->tasks()
    ->list()
    ->all();
```

## Response Structure

### Task

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Task ID |
| `subject` | `?string` | Task subject |
| `description` | `?string` | Task description |
| `status` | `?string` | Task status |
| `dueDate` | `?DateTimeImmutable` | Due date |
